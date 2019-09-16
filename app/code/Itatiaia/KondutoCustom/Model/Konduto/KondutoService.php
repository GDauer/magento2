<?php

namespace Itatiaia\KondutoCustom\Model\Konduto;

use Magento\Sales\Model\ResourceModel\Attribute as OrderAttributeResource;
use Konduto\Antifraud\Model\KondutoService as OriginalKondutoService;

/**
 * Class KondutoService
 * @package Konduto\Antifraud\Model
 */
class KondutoService extends OriginalKondutoService
{
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
        \Magento\Sales\Model\Order\Email\Sender\InvoiceSender $invoiceSender
    ) {
        parent::__construct(
            $helper,
            $queueFactory,
            $queueRepository,
            $historyFactory,
            $historyRepository,
            $collectionFactory,
            $orderRepository,
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
}
