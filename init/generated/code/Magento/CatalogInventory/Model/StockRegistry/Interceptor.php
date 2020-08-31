<?php
namespace Magento\CatalogInventory\Model\StockRegistry;

/**
 * Interceptor class for @see \Magento\CatalogInventory\Model\StockRegistry
 */
class Interceptor extends \Magento\CatalogInventory\Model\StockRegistry implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\CatalogInventory\Api\StockConfigurationInterface $stockConfiguration, \Magento\CatalogInventory\Model\Spi\StockRegistryProviderInterface $stockRegistryProvider, \Magento\CatalogInventory\Api\StockItemRepositoryInterface $stockItemRepository, \Magento\CatalogInventory\Api\StockItemCriteriaInterfaceFactory $criteriaFactory, \Magento\Catalog\Model\ProductFactory $productFactory)
    {
        $this->___init();
        parent::__construct($stockConfiguration, $stockRegistryProvider, $stockItemRepository, $criteriaFactory, $productFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function updateStockItemBySku($productSku, \Magento\CatalogInventory\Api\Data\StockItemInterface $stockItem)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'updateStockItemBySku');
        if (!$pluginInfo) {
            return parent::updateStockItemBySku($productSku, $stockItem);
        } else {
            return $this->___callPlugins('updateStockItemBySku', func_get_args(), $pluginInfo);
        }
    }
}
