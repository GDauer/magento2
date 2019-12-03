<?php

namespace Itatiaia\KondutoCustom\Model\Konduto;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Model\ResourceModel\Attribute as OrderAttributeResource;
use Konduto\Antifraud\Model\KondutoService as OriginalKondutoService;
use Magento\Sales\Model\Order\StatusFactory;

/**
 * Class KondutoService
 * @package Konduto\Antifraud\Model
 */
class KondutoService extends OriginalKondutoService
{
    /**
     * @var \Magento\Sales\Model\Order\Status\HistoryFactory
     */
    protected $orderHistoryFactory;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $orderFactory;

    /**
     * @var \Adyen\Payment\Gateway\Http\TransferFactory
     */
    private $transferFactory;

    /**
     * @var \Adyen\Payment\Helper\Requests
     */
    private $adyenRequestHelper;

    /**
     * @var \Adyen\Payment\Gateway\Http\Client\TransactionCapture
     */
    private $transactionCapture;

    /**
     * @var \Adyen\Payment\Gateway\Http\Client\TransactionCancel
     */
    private $transactionCancel;

    /**
     * @var \Adyen\Payment\Helper\Data
     */
    private $adyenHelper;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory
     */
    protected $orderStatusCollection;

    /**
     * @var \Itatiaia\CustomMercadoPago\Http\Order\Payment
     */
    protected $mercadoPagoHttpPayment;

    public function __construct(
        \Konduto\Antifraud\Helper\Data $helper,
        \Konduto\Antifraud\Model\QueueFactory $queueFactory,
        \Konduto\Antifraud\Model\QueueRepository $queueRepository,
        \Konduto\Antifraud\Model\HistoryFactory $historyFactory,
        \Konduto\Antifraud\Model\HistoryRepository $historyRepository,
        \Konduto\Antifraud\Model\ResourceModel\Queue\CollectionFactory $collectionFactory,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Konduto\Antifraud\Model\Konduto\OrderData $orderData,
        \Magento\Framework\App\State $state,
        OrderAttributeResource $orderAttribute,
        \Magento\Sales\Model\Order $orderModel,
        \Magento\Sales\Model\Service\InvoiceService $invoiceService,
        \Magento\Framework\DB\Transaction $transaction,
        \Magento\Sales\Model\Order\Email\Sender\InvoiceSender $invoiceSender,
        \Magento\Sales\Model\Order\Status\HistoryFactory $orderHistoryFactory,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Adyen\Payment\Gateway\Http\TransferFactory $transferFactory,
        \Adyen\Payment\Helper\Requests $adyenRequestHelper,
        \Adyen\Payment\Gateway\Http\Client\TransactionCapture $transactionCapture,
        \Adyen\Payment\Gateway\Http\Client\TransactionCancel $transactionCancel,
        \Adyen\Payment\Helper\Data $adyenHelper,
        \Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory $orderStatusCollection,
        \Itatiaia\CustomMercadoPago\Http\Order\Payment $mercadoPagoHttpPayment
    )
    {
        $this->orderHistoryFactory = $orderHistoryFactory;
        $this->orderFactory = $orderFactory;
        $this->transferFactory = $transferFactory;
        $this->adyenRequestHelper = $adyenRequestHelper;
        $this->transactionCapture = $transactionCapture;
        $this->transactionCancel = $transactionCancel;
        $this->adyenHelper = $adyenHelper;
        $this->orderStatusCollection = $orderStatusCollection;
        $this->mercadoPagoHttpPayment = $mercadoPagoHttpPayment;
        parent::__construct(
            $orderRepository,
            $queueRepository,
            $historyRepository,
            $queueFactory,
            $historyFactory,
            $collectionFactory,
            $helper,
            $orderData,
            $state,
            $orderAttribute,
            $orderModel,
            $invoiceService,
            $transaction,
            $invoiceSender
        );
    }

    public function updateHistory($orderId, $newStatus)
    {
        $nowTime = $this->helper->getNowDateTime();
        try {
            $history = $this->historyRepository->getById($orderId);
        } catch (NoSuchEntityException $e) {
            // if history doesn't exist, we create a new one
            $history = $this->historyFactory->create();
            $history->setOrderId($orderId);
            $history->setCreatedAt($nowTime);
        }
        $history->setStatus($newStatus);
        $history->setUpdatedAt($nowTime);
        $this->historyRepository->save($history);

        // Setting order history
        /** @var \Magento\Sales\Model\Order $order */
        $order = $this->orderFactory->create()->load($orderId);
        $history = $this->orderHistoryFactory->create()
            ->setStatus($this->helper->getStatusCode($newStatus))
            ->setComment('New Konduto status - ' . $newStatus)
            ->setEntityName('order')
            ->setOrder($order);

        $history->save();
    }

    public function updateOrder($params)
    {
        $isValidSignature = $this->helper->validateSignature($params);

        /* FIXING STRCASE ERROR*/
        $params['status'] = strtolower($params['status']);

        if ($isValidSignature) {
            $kondutoResponse = $this->updateOrderStatus($params['order_id'], $params['status']);
        }

        if (!isset($kondutoResponse) || $kondutoResponse === false) {
            return false;
        }

        /** @var \Magento\Sales\Model\Order $order */
        $order = $this->orderFactory->create()->loadByIncrementId($params['order_id']);
        if (!$order->getId()) {
            return false;
        }
        $this->helper->log('WEBHOOK ORDER ID ' . $order->getId(), 'info');
        $this->updateHistory($order->getId(), $params['status']);
        $oldStatus = strtolower($order->getKondutoStatus());
        $order->setKondutoStatus($params['status']);
        $this->helper->log('============ ANTES IF =========' , 'info');

        if($order->getId() == '5723') {
            return true;
        }

        if ($this->helper->getAutomaticKondutoUpdate()) {

            $this->helper->log('WEBHOOK SENDING STATE ' . $params['status'], 'info');
            $statusCode = $this->helper->getStatusCode($params['status']);

            $this->helper->log('STATUS ENVIADO DA KONDUTO ' . $params['status'], 'info');
            $this->helper->log('ORDER STATE ' . $order->getState(), 'info');
            $this->helper->log('ORDER STATUS ' . $order->getStatus(), 'info');
            $this->helper->log('OLD KONDUTO STATUS ' . $oldStatus, 'info');

            if($order->getState() == 'canceled' || $order->getState() == 'complete') {
                return true;
            }

            if($params['status'] == 'approved' && $order->getState() == 'processing') {
                return true;
            }

            $this->helper->log('WEBHOOK SETTING ORDER STATE ' . json_encode($this->getOrderState($statusCode)), 'info');
            $this->helper->log('WEBHOOK SETTING ORDER STATUS ' . $statusCode, 'info');
            $order->setState($this->getOrderState($statusCode));
            $order->setStatus($statusCode);

            $this->helper->log('New order state ' . $order->getState(), 'info');
            $this->helper->log('New order status ' . $order->getStatus(), 'info');

            if ($params['status'] == 'approved') {
                $this->sendCapture($order);
            } elseif (
                $params['status'] == 'declined'
                || $params['status'] == 'not_authorized'
                || $params['status'] == 'canceled'
            ) {
                $this->helper->log('WEBHOOK SEND CANCEL IS TRUE -- ', 'info');
                $this->sendCancel($order);
            } else {
                $this->helper->log('ELSE PERDIDO ', 'info');
            }
        }
        $this->helper->log('FINAL ORDER STATE ' . $order->getState(), 'info');
        $this->helper->log('FINAL ORDER STATUS ' . $order->getStatus(), 'info');
        $order->save();
        return true;
    }

    public function sendToAdyen($order)
    {
        $this->helper->log('WEBHOOK APPROVING', 'info');
        // TODO -> refactor to change status and implement adyen request on Observer

        // Merchant account data builder
        $request = $this->createRequest($order);
        $transferObject = $this->transferFactory->create($request);
        $paymentsResponse = $this->transactionCapture->placeRequest($transferObject);

        $this->helper->log('WEBHOOK PAYMENT RESPONSE' . json_encode($paymentsResponse), 'info');
        if (!isset($paymentsResponse['response']) || !$paymentsResponse['response'] == '[capture-received]'
            || !isset($paymentsResponse['pspReference']) || !$paymentsResponse['pspReference'] == $order->getPayment()->getCcTransId()
        ) {
            $this->helper->log('ERROR SENDING CAPTURE DATA TO ADYEN - REQUEST WAS ' . json_encode($request), 'warning');
            // TODO -> return error to Konduto
        }
    }

    /**
     * @param $order
     * @return array|bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function sendToMercadoPago($order)
    {
        $this->helper->log('WEBHOOK CAPTURING ON MERCADOPAGO', 'info');
        $resp = $this->mercadoPagoHttpPayment->capture($order);
        $this->helper->log('MERCADOPAGO RET - ' . json_encode($resp), 'info');

        return $resp;
    }

    public function sendCapture($order)
    {
        $payment = $order->getPayment();
        $method = $payment->getMethod();

        $this->helper->log('SEND CAPTURE ', 'info');

        if (strpos($method, 'mercadopago') !== false) {
            $this->helper->log('SEND CAPTURE MERCADO BEFORE ', 'info');
            return $this->sendToMercadoPago($order);
        }
        $this->helper->log('SEND ADYEN BEFORE ', 'info');
        return $this->sendToAdyen($order);
    }

    public function sendCancel($order)
    {
        if (strpos($order->getPayment()->getMethod(), 'mercadopago') !== false) {
            return $this->sendToMercadoPagoCancel($order);
        }

        return $this->sendToAdyenCancel($order);
    }

    public function sendToMercadoPagoCancel($order)
    {
        $this->helper->log('WEBHOOK CANCELLING MERCADOPAGO', 'info');
        $resp = $this->mercadoPagoHttpPayment->cancel($order);
        $this->helper->log('MERCADOPAGO RET - ' . json_encode($resp), 'info');

        return $resp;
    }

    public function sendToAdyenCancel($order)
    {
        $this->helper->log('WEBHOOK APPROVING', 'info');

        // Merchant account data builder
        $request = $this->createCancelRequest($order);
        $transferObject = $this->transferFactory->create($request);
        $paymentsResponse = $this->transactionCancel->placeRequest($transferObject);

        $this->helper->log('WEBHOOK PAYMENT CANCEL RESPONSE' . json_encode($paymentsResponse), 'info');
        if (!isset($paymentsResponse['response']) || !$paymentsResponse['response'] == '[cancel-received]'
            || !isset($paymentsResponse['pspReference']) || !$paymentsResponse['pspReference'] == $order->getPayment()->getCcTransId()
        ) {
            $this->helper->log('ERROR SENDING CAPTURE DATA TO ADYEN - REQUEST WAS ' . json_encode($request), 'warning');
        }
    }

    /**
     * @param $order
     * @return mixed
     */
    public function createRequest($order)
    {
        $payment = $order->getPayment();
        $paymentMethod = $payment->getMethod();
        $storeId = $order->getStoreId();

        $currency = $order->getOrderCurrencyCode();
        $amount = $this->adyenHelper->formatAmount($order->getGrandTotal(), $currency);
        $modificationAmount = ['currency' => $currency, 'value' => $amount];

        $request = [
            "modificationAmount" => $modificationAmount,
            "reference" => $order->getIncrementId(),
            "originalReference" => $payment->getCcTransId()
        ];

        return $this->adyenRequestHelper->buildMerchantAccountData($request, $paymentMethod, $storeId);
    }

    /**
     * @param $order
     * @return mixed
     */
    public function createCancelRequest($order)
    {
        $payment = $order->getPayment();
        $paymentMethod = $payment->getMethod();
        $storeId = $order->getStoreId();

        $request = [
            "reference" => $order->getIncrementId(),
            "originalReference" => $payment->getCcTransId()
        ];

        return $this->adyenRequestHelper->buildMerchantAccountData($request, $paymentMethod, $storeId);
    }

    public function getOrderState($status)
    {
        $statusObject = $this->orderStatusCollection->create()
            ->addFieldToFilter('main_table.status', $status)
            ->addFieldToFilter('state_table.is_default', true)
            ->joinStates()
            ->getFirstItem();

        $this->helper->log('==== STATE TO SET ====' . $statusObject->getState(), 'info');

        return $statusObject->getState();
    }
}