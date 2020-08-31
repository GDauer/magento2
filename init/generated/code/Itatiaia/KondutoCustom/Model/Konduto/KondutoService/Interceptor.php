<?php
namespace Itatiaia\KondutoCustom\Model\Konduto\KondutoService;

/**
 * Interceptor class for @see \Itatiaia\KondutoCustom\Model\Konduto\KondutoService
 */
class Interceptor extends \Itatiaia\KondutoCustom\Model\Konduto\KondutoService implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Konduto\Antifraud\Helper\Data $helper, \Konduto\Antifraud\Model\QueueFactory $queueFactory, \Konduto\Antifraud\Model\QueueRepository $queueRepository, \Konduto\Antifraud\Model\HistoryFactory $historyFactory, \Konduto\Antifraud\Model\HistoryRepository $historyRepository, \Konduto\Antifraud\Model\ResourceModel\Queue\CollectionFactory $collectionFactory, \Magento\Sales\Api\OrderRepositoryInterface $orderRepository, \Konduto\Antifraud\Model\Konduto\OrderData $orderData, \Magento\Framework\App\State $state, \Magento\Sales\Model\ResourceModel\Attribute $orderAttribute, \Magento\Sales\Model\Order $orderModel, \Magento\Sales\Model\Service\InvoiceService $invoiceService, \Magento\Framework\DB\Transaction $transaction, \Magento\Sales\Model\Order\Email\Sender\InvoiceSender $invoiceSender, \Magento\Sales\Model\Order\Status\HistoryFactory $orderHistoryFactory, \Magento\Sales\Model\OrderFactory $orderFactory, \Adyen\Payment\Gateway\Http\TransferFactory $transferFactory, \Adyen\Payment\Helper\Requests $adyenRequestHelper, \Adyen\Payment\Gateway\Http\Client\TransactionCapture $transactionCapture, \Adyen\Payment\Gateway\Http\Client\TransactionCancel $transactionCancel, \Adyen\Payment\Helper\Data $adyenHelper, \Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory $orderStatusCollection, \Itatiaia\CustomMercadoPago\Http\Order\Payment $mercadoPagoHttpPayment)
    {
        $this->___init();
        parent::__construct($helper, $queueFactory, $queueRepository, $historyFactory, $historyRepository, $collectionFactory, $orderRepository, $orderData, $state, $orderAttribute, $orderModel, $invoiceService, $transaction, $invoiceSender, $orderHistoryFactory, $orderFactory, $transferFactory, $adyenRequestHelper, $transactionCapture, $transactionCancel, $adyenHelper, $orderStatusCollection, $mercadoPagoHttpPayment);
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
