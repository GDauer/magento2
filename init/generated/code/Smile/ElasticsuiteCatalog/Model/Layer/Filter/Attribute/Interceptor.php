<?php
namespace Smile\ElasticsuiteCatalog\Model\Layer\Filter\Attribute;

/**
 * Interceptor class for @see \Smile\ElasticsuiteCatalog\Model\Layer\Filter\Attribute
 */
class Interceptor extends \Smile\ElasticsuiteCatalog\Model\Layer\Filter\Attribute implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Catalog\Model\Layer $layer, \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder, \Magento\Framework\Filter\StripTags $tagFilter, \Magento\Framework\Escaper $escaper, \Smile\ElasticsuiteCatalog\Helper\ProductAttribute $mappingHelper, array $data = array())
    {
        $this->___init();
        parent::__construct($filterItemFactory, $storeManager, $layer, $itemDataBuilder, $tagFilter, $escaper, $mappingHelper, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function addFacetToCollection($config = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addFacetToCollection');
        if (!$pluginInfo) {
            return parent::addFacetToCollection($config);
        } else {
            return $this->___callPlugins('addFacetToCollection', func_get_args(), $pluginInfo);
        }
    }
}
