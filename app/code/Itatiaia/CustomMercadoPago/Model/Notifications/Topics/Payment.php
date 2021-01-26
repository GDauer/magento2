<?php

namespace Itatiaia\CustomMercadoPago\Model\Notifications\Topics;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DB\TransactionFactory;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\CreditmemoFactory;
use Magento\Sales\Model\Order\Email\Sender\InvoiceSender;
use Magento\Sales\Model\Order\Email\Sender\OrderCommentSender;
use Magento\Sales\Model\Order\Email\Sender\OrderSender;
use Magento\Sales\Model\OrderFactory;
use Magento\Sales\Model\ResourceModel\Status\Collection as StatusFactory;
use Magento\Sales\Model\Service\InvoiceService;
use Magento\Store\Model\ScopeInterface;
use Itatiaia\CustomMercadoPago\Helper\ConfigData;
use MercadoPago\Core\Helper\Data as mpHelper;
use MercadoPago\Core\Helper\Message\MessageInterface;
use MercadoPago\Core\Helper\Response;
use MercadoPago\Core\Model\Core;

class Payment extends \MercadoPago\Core\Model\Notifications\Topics\Payment
{

    /**
     * @var Order\Payment\Transaction\Builder
     */
    private $transactionBuilder;

    /**
     * @var ScopeConfigInterface 
     */
    protected $_scopeConfig;

    /**
     * @var OrderSender
     */
    protected $_orderSender;

    /**
     * @var mpHelper
     */
    protected $_dataHelper;


    public function __construct(
        mpHelper $mpHelper,
        ScopeConfigInterface $scopeConfig,
        Core $coreModel,
        OrderFactory $orderFactory,
        CreditmemoFactory $creditmemoFactory,
        MessageInterface $messageInterface,
        StatusFactory $statusFactory,
        OrderSender $orderSender,
        OrderCommentSender $orderCommentSender,
        TransactionFactory $transactionFactory,
        InvoiceSender $invoiceSender,
        InvoiceService $invoiceService,
        Order\Payment\Transaction\Builder $transactionBuilder

    )
    {
        parent::__construct($mpHelper, $scopeConfig, $coreModel, $orderFactory, $creditmemoFactory, $messageInterface, $statusFactory, $orderSender, $orderCommentSender, $transactionFactory, $invoiceSender, $invoiceService);
        $this->transactionBuilder = $transactionBuilder;
        $this->_scopeConfig = $scopeConfig;
        $this->_orderSender = $orderSender;
        $this->_dataHelper = $mpHelper;
    }

    const STATUS = 'payment_review';

    public function updateStatusOrderByPayment($payment)
    {
        $order = parent::getOrderByIncrementId($payment['external_reference']);

        if (!$order->getId()) {
            $message = "Mercado Pago - The order was not found in Magento. You will not be able to follow the process without this information.";
            return [ "httpStatus" => Response::HTTP_NOT_FOUND, "message" => $message, "data" => $payment['external_reference']];
        }

        $message = parent::getMessage($payment);
        $statusAlreadyUpdated = $this->checkStatusAlreadyUpdated($payment, $order);
        $newOrderStatus = $this->getConfigStatus($payment, $order->canCreditmemo());
        $currentOrderStatus = $order->getState();
        if ($statusAlreadyUpdated) {
            $orderPayment = $order->getPayment();
            $orderPayment->setAdditionalInformation("paymentResponse", $payment);
            $order->save();

            $messageHttp = "Mercado Pago - Status has already been updated.";
            return [
                "httpStatus" => Response::HTTP_OK,
                "message" => $messageHttp,
                "data" => [
                    "message" => $message,
                    "order_id" => $order->getIncrementId(),
                    "current_order_status" => $currentOrderStatus,
                    "new_order_status" => $newOrderStatus
                ]
            ];
        }

        $order = $this->setStatusAndComment($order, $newOrderStatus, $message);

        $this->sendEmailCreateOrUpdate($order, $message);
        $responseInvoice = false;
        if ($payment['status'] == 'approved') {
            $responseInvoice = $this->createInvoice($order, $message);
            $this->addCardInCustomer($payment);
        }
        $order->getPayment()->setAdditionalInformation("paymentResponse", $payment);
        $order->save();

        $messageHttp = "Mercado Pago - Status successfully updated.";
        return [
            "httpStatus" => Response::HTTP_OK,
            "message" => $messageHttp,
            "data" => [
                "message" => $message,
                "order_id" => $order->getIncrementId(),
                "new_order_status" => $newOrderStatus,
                "old_order_status" => $currentOrderStatus,
                "created_invoice" => $responseInvoice
            ]
        ];
    }

    public function setStatusAndComment($order, $newStatusOrder, $message)
    {
        if ($order->getState() !== Order::STATE_COMPLETE) {
            if ($newStatusOrder == 'canceled' && $order->getState() != 'canceled') {
                $order->cancel();
            } else {
                $order->setState($this->_getAssignedState($newStatusOrder));
            }
            $order->addStatusToHistory($newStatusOrder, $message, true);
        }

        $payment = $order->getPayment();
        $method = $payment->getMethod();

        if($order->getState() == 'new' && $method == 'mercadopago_customticket') {
            $order->setState(self::STATUS);
            $order->setStatus(self::STATUS);
        }

        return $order;

    }

    public function createInvoice($order, $message)
    {
        if (!$order->hasInvoices()) {
            $invoice = $order->prepareInvoice();

            $payId = (int)preg_replace('/\D+/','',$message);
            $invoice->setTransactionId($payId);

            $invoice->register()->pay();
            $invoice->addComment(str_replace("<br/>", "", $message), false, true);

            $transaction = $this->_transactionFactory->create();
            $transaction->addObject($invoice);
            $transaction->addObject($invoice->getOrder());
            $transaction->save();

            $salesTransaction = $this->transactionBuilder->setPayment($order->getPayment())
                ->setOrder($order)
                ->setTransactionId($payId)
                ->build(\Magento\Sales\Api\Data\TransactionInterface::TYPE_AUTH);

            $salesTransaction->setIsClosed(false);

            $salesTransaction->save();

            $this->_invoiceSender->send($invoice, true, $message);
            return true;
        }

        return false;
    }

    /**
     * @param $paymentResponse
     * @param $order
     * @return bool
     */
    public function checkStatusAlreadyUpdated($paymentResponse, $order)
    {
        $orderUpdated = false;
        $statusToUpdate = $this->getConfigStatus($paymentResponse, false);
        $commentsObject = $order->getStatusHistoryCollection(true);
        foreach ($commentsObject as $commentObj) {
            if ($commentObj->getStatus() == $statusToUpdate) {
                $orderUpdated = true;
            }
        }

        return $orderUpdated;
    }

    /**
     * @param $order
     * @param $payment
     * @param $message
     * @return mixed
     */
    public function updateStatus($order, $payment, $message)
    {
        if ($order->getState() !== Order::STATE_COMPLETE) {
            $statusOrder = $this->getConfigStatus($payment, $order->canCreditmemo());

            $emailAlreadySent = false;
            $emailOrderCreate = $this->_scopeConfig->getValue(ConfigData::PATH_ADVANCED_EMAIL_CREATE, ScopeInterface::SCOPE_STORE);

            if ($statusOrder == 'canceled') {
                $order->cancel();
            } else {
                $order->setState($this->_getAssignedState($statusOrder));
            }

            $order->addStatusToHistory($statusOrder, $message, true);
            if ($emailOrderCreate) {
                if (!$order->getEmailSent()) {
                    $this->_orderSender->send($order, true);
                    $emailAlreadySent = true;
                }
            }

            if ($emailAlreadySent === false) {
                $statusEmail = $this->_scopeConfig->getValue(ConfigData::PATH_ADVANCED_EMAIL_UPDATE, ScopeInterface::SCOPE_STORE);
                $statusEmailList = explode(",", $statusEmail);
                if (in_array($payment['status'], $statusEmailList)) {
                    $this->_orderSender->send($order, $notify = '1', str_replace("<br/>", "", $message));
                }
            }
        }

        $this->_dataHelper->log("Update order", 'mercadopago-basic.log', $order->getData());
        $this->_dataHelper->log($message, 'mercadopago-basic.log');

        return $order->save();
    }

    /**
     * @param $payment
     * @param $isCanCreditMemo
     * @return mixed
     */
    public function getConfigStatus($payment, $isCanCreditMemo)
    {
        $pathStatus = "PATH_ORDER_" . strtoupper($payment['status']);
        $path = constant('\Itatiaia\CustomMercadoPago\Helper\ConfigData::' . $pathStatus);
        $status = $this->_scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE);
        if ($payment['status'] == 'approved') {
            if ($payment['status_detail'] == 'partially_refunded' && $isCanCreditMemo) {
                $status = $this->_scopeConfig->getValue(ConfigData::PATH_ORDER_PARTIALLY_REFUNDED, ScopeInterface::SCOPE_STORE);
            }
        }

        if (empty($status)) {
            $status = $this->_scopeConfig->getValue(ConfigData::PATH_ORDER_PENDING, ScopeInterface::SCOPE_STORE);
        }

        return $status;
    }
}