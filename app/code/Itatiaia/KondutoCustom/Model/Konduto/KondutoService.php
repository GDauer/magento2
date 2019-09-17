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
     * KondutoService constructor.
     * @param \Konduto\Antifraud\Helper\Data $helper,
     * @param \Konduto\Antifraud\Model\QueueFactory $queueFactory,
     * @param \Konduto\Antifraud\Model\QueueRepository $queueRepository,
     * @param \Konduto\Antifraud\Model\HistoryFactory $historyFactory,
     * @param \Konduto\Antifraud\Model\HistoryRepository $historyRepository,
     * @param \Konduto\Antifraud\Model\ResourceModel\Queue\CollectionFactory $collectionFactory,
     * @param \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
     * @param \Konduto\Antifraud\Model\Konduto\OrderData $orderData,
     * @param \Magento\Framework\App\State $state,
     * @param OrderAttributeResource $orderAttribute,
     * @param \Magento\Sales\Model\Order $orderModel,
     * @param \Magento\Sales\Model\Service\InvoiceService $invoiceService,
     * @param \Magento\Framework\DB\Transaction $transaction,
     * @param \Magento\Sales\Model\Order\Email\Sender\InvoiceSender $invoiceSender
     */
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
        \Magento\Sales\Model\OrderFactory $orderFactory
    ) {
        $this->orderHistoryFactory = $orderHistoryFactory;
        $this->orderFactory = $orderFactory;
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

    /**
     * Method for sending orders and returning status.
     *
     * @param $orderId
     */
    public function analysis($orderId)
    {
        $this->helper->log('ANALYSIS : ' . $orderId, 'info');
        parent::analysis($orderId);
    }

    public function updateHistory($orderId, $newStatus)
    {
        $nowTime = $this->helper->getNowDateTime();
        try {
            $history = $this->historyRepository->getById($orderId);
        } catch (NoSuchEntityException $e) {
            $history = $this->historyFactory->create();
            $history->setOrderId($orderId);
            $history->setCreatedAt($nowTime);
        }
        $history->setStatus($newStatus);
        $history->setUpdatedAt($nowTime);
        $this->historyRepository->save($history);

        // Setting order history
        $order = $this->orderFactory->create()->load($orderId);
        $history = $this->orderHistoryFactory->create()
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

        $order = $this->orderFactory->create()->loadByIncrementId($params['order_id']);
        if (!$order->getId()) {
            return false;
        }
        $this->updateHistory($order->getId(), $params['status']);
        $order->setKondutoStatus($params['status']);
        if ($this->helper->getAutomaticKondutoUpdate()) {
            $order->setStatus($this->helper->getStatusCode($params['status']));
            if ($params['status'] == 'approved') {
                $this->createInvoice($order);
            }
        }

        $order->save();
        return true;
    }
}
