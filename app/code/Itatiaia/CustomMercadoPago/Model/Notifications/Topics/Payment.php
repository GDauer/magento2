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
        $newOrderStatus = parent::getConfigStatus($payment, $order->canCreditmemo());
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
}