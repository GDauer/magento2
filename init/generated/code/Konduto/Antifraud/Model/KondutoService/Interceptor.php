<?php
namespace Konduto\Antifraud\Model\KondutoService;

/**
 * Interceptor class for @see \Konduto\Antifraud\Model\KondutoService
 */
class Interceptor extends \Konduto\Antifraud\Model\KondutoService implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Sales\Api\OrderRepositoryInterface $orderRepository, \Konduto\Antifraud\Model\QueueRepository $queueRepository, \Konduto\Antifraud\Model\HistoryRepository $historyRepository, \Konduto\Antifraud\Model\QueueFactory $queueFactory, \Konduto\Antifraud\Model\HistoryFactory $historyFactory, \Konduto\Antifraud\Model\ResourceModel\Queue\CollectionFactory $collectionFactory, \Konduto\Antifraud\Helper\Data $helper, \Konduto\Antifraud\Model\Konduto\OrderData $orderData, \Magento\Framework\App\State $state, \Magento\Sales\Model\ResourceModel\Attribute $orderAttribute, \Magento\Sales\Model\Order $orderModel, \Magento\Sales\Model\Service\InvoiceService $invoiceService, \Magento\Framework\DB\Transaction $transaction, \Magento\Sales\Model\Order\Email\Sender\InvoiceSender $invoiceSender)
    {
        $this->___init();
        parent::__construct($orderRepository, $queueRepository, $historyRepository, $queueFactory, $historyFactory, $collectionFactory, $helper, $orderData, $state, $orderAttribute, $orderModel, $invoiceService, $transaction, $invoiceSender);
    }

    /**
     * {@inheritdoc}
     */
    public function analysis($orderId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'analysis');
        if (!$pluginInfo) {
            return parent::analysis($orderId);
        } else {
            return $this->___callPlugins('analysis', func_get_args(), $pluginInfo);
        }
    }
}
