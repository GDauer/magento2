<?php
namespace Criteo\OneTag\Controller\Feed\Index;

/**
 * Interceptor class for @see \Criteo\OneTag\Controller\Feed\Index
 */
class Interceptor extends \Criteo\OneTag\Controller\Feed\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\App\Request\Http $request, \Magento\Catalog\Model\ProductRepository $productRepository, \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockRepository, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Framework\App\ResourceConnection $connection, \Magento\Catalog\Helper\Category $categoryHelper, \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable $productTypeConfigurable, \Magento\Eav\Model\ResourceModel\Entity\Attribute $eavAttribute, \Magento\Framework\App\ProductMetadataInterface $productMetadata)
    {
        $this->___init();
        parent::__construct($context, $request, $productRepository, $stockRepository, $scopeConfig, $connection, $categoryHelper, $productTypeConfigurable, $eavAttribute, $productMetadata);
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'dispatch');
        if (!$pluginInfo) {
            return parent::dispatch($request);
        } else {
            return $this->___callPlugins('dispatch', func_get_args(), $pluginInfo);
        }
    }
}
