<?php
namespace Smile\ElasticsuiteCatalog\Block\Plugin\Adminhtml\Product\Attribute\Edit\Tab\FrontPlugin;

/**
 * Interceptor class for @see \Smile\ElasticsuiteCatalog\Block\Plugin\Adminhtml\Product\Attribute\Edit\Tab\FrontPlugin
 */
class Interceptor extends \Smile\ElasticsuiteCatalog\Block\Plugin\Adminhtml\Product\Attribute\Edit\Tab\FrontPlugin implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Config\Model\Config\Source\Yesno $booleanSource, \Magento\CatalogSearch\Model\Source\Weight $weightSource, \Magento\Framework\Registry $registry, \Smile\ElasticsuiteCatalog\Model\Attribute\Source\FilterSortOrder $filterSortOrder)
    {
        $this->___init();
        parent::__construct($booleanSource, $weightSource, $registry, $filterSortOrder);
    }

    /**
     * {@inheritdoc}
     */
    public function aroundSetForm(\Magento\Catalog\Block\Adminhtml\Product\Attribute\Edit\Tab\Front $subject, \Closure $proceed, \Magento\Framework\Data\Form $form)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'aroundSetForm');
        if (!$pluginInfo) {
            return parent::aroundSetForm($subject, $proceed, $form);
        } else {
            return $this->___callPlugins('aroundSetForm', func_get_args(), $pluginInfo);
        }
    }
}
