<?php
namespace Magento\SalesSampleData\Model\Order\Processor;

/**
 * Interceptor class for @see \Magento\SalesSampleData\Model\Order\Processor
 */
class Interceptor extends \Magento\SalesSampleData\Model\Order\Processor implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Registry $coreRegistry, \Magento\Framework\Phrase\Renderer\CompositeFactory $rendererCompositeFactory, \Magento\Sales\Model\AdminOrder\CreateFactory $createOrderFactory, \Magento\Customer\Api\CustomerRepositoryInterface $customerFactory, \Magento\Backend\Model\Session\QuoteFactory $sessionQuoteFactory, \Magento\Framework\DB\TransactionFactory $transactionFactory, \Magento\Sales\Model\OrderFactory $orderFactory, \Magento\Sales\Model\Service\InvoiceService $invoiceManagement, \Magento\Shipping\Controller\Adminhtml\Order\ShipmentLoaderFactory $shipmentLoaderFactory, \Magento\Sales\Controller\Adminhtml\Order\CreditmemoLoaderFactory $creditmemoLoaderFactory, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Sales\Api\CreditmemoManagementInterface $creditmemoManagement)
    {
        $this->___init();
        parent::__construct($coreRegistry, $rendererCompositeFactory, $createOrderFactory, $customerFactory, $sessionQuoteFactory, $transactionFactory, $orderFactory, $invoiceManagement, $shipmentLoaderFactory, $creditmemoLoaderFactory, $storeManager, $creditmemoManagement);
    }

    /**
     * {@inheritdoc}
     */
    public function getCreditmemoData(\Magento\Sales\Model\Order\Item $orderItem)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCreditmemoData');
        if (!$pluginInfo) {
            return parent::getCreditmemoData($orderItem);
        } else {
            return $this->___callPlugins('getCreditmemoData', func_get_args(), $pluginInfo);
        }
    }
}
