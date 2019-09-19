<?php

namespace Itatiaia\KondutoCustom\Model\Konduto;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Model\ResourceModel\Attribute as OrderAttributeResource;
use Konduto\Antifraud\Model\KondutoService as OriginalKondutoService;

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
     * @var \Adyen\Payment\Helper\Data
     */
    private $adyenHelper;

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
        \Adyen\Payment\Helper\Data $adyenHelper
    ) {
        $this->orderHistoryFactory = $orderHistoryFactory;
        $this->orderFactory = $orderFactory;
        $this->transferFactory = $transferFactory;
        $this->adyenRequestHelper = $adyenRequestHelper;
        $this->transactionCapture = $transactionCapture;
        $this->adyenHelper = $adyenHelper;
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
        $order->setKondutoStatus($params['status']);
        if ($this->helper->getAutomaticKondutoUpdate()) {
            $this->helper->log('WEBHOOK SETTING STATUS ' . json_encode($this->helper->getStatusCode($params['status'])), 'info');
            $order->setStatus($this->helper->getStatusCode($params['status']));
            if ($params['status'] == 'approved') {
                $this->helper->log('WEBHOOK APPROVING', 'info');
                // TODO -> refactor to change status and implement adyen request on Observer

                // Merchant account data builder
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

                $request = $this->adyenRequestHelper->buildMerchantAccountData($request, $paymentMethod, $storeId);

                $this->helper->log('WEBHOOK SENDING REQUEST TO ADYEN' . json_encode($request), 'info');
                $transferObject = $this->transferFactory->create($request);
                $paymentsResponse = $this->transactionCapture->placeRequest($transferObject);

                $this->helper->log('WEBHOOK PAYMENT RESPONSE' . json_encode($paymentsResponse), 'info');
                if (!isset($paymentsResponse['response']) || !$paymentsResponse['response'] == '[capture-received]'
                    || !isset($paymentsResponse['pspReference']) || !$paymentsResponse['pspReference'] == $payment->getCcTransId()
                ) {
                    $this->helper->log('ERROR SENDING CAPTURE DATA TO ADYEN - REQUEST WAS ' . json_encode($request), 'warning');
                    // TODO -> return error to Konduto
                }
            }
        }

        $order->save();
        return true;
    }
}
