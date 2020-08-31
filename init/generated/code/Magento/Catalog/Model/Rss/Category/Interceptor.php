<?php
namespace Magento\Catalog\Model\Rss\Category;

/**
 * Interceptor class for @see \Magento\Catalog\Model\Rss\Category
 */
class Interceptor extends \Magento\Catalog\Model\Rss\Category implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Catalog\Model\Layer\Resolver $layerResolver, \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory, \Magento\Catalog\Model\Product\Visibility $visibility)
    {
        $this->___init();
        parent::__construct($layerResolver, $collectionFactory, $visibility);
    }

    /**
     * {@inheritdoc}
     */
    public function getProductCollection(\Magento\Catalog\Model\Category $category, $storeId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getProductCollection');
        if (!$pluginInfo) {
            return parent::getProductCollection($category, $storeId);
        } else {
            return $this->___callPlugins('getProductCollection', func_get_args(), $pluginInfo);
        }
    }
}
