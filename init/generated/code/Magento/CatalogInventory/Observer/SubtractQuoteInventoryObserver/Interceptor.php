<?php
namespace Magento\CatalogInventory\Observer\SubtractQuoteInventoryObserver;

/**
 * Interceptor class for @see \Magento\CatalogInventory\Observer\SubtractQuoteInventoryObserver
 */
class Interceptor extends \Magento\CatalogInventory\Observer\SubtractQuoteInventoryObserver implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\CatalogInventory\Api\StockManagementInterface $stockManagement, \Magento\CatalogInventory\Observer\ProductQty $productQty, \Magento\CatalogInventory\Observer\ItemsForReindex $itemsForReindex)
    {
        $this->___init();
        parent::__construct($stockManagement, $productQty, $itemsForReindex);
    }

    /**
     * {@inheritdoc}
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'execute');
        if (!$pluginInfo) {
            return parent::execute($observer);
        } else {
            return $this->___callPlugins('execute', func_get_args(), $pluginInfo);
        }
    }
}
