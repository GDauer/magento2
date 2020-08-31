<?php
namespace Magento\Catalog\Block\Adminhtml\Product\Edit\Button\AddAttribute;

/**
 * Interceptor class for @see \Magento\Catalog\Block\Adminhtml\Product\Edit\Button\AddAttribute
 */
class Interceptor extends \Magento\Catalog\Block\Adminhtml\Product\Edit\Button\AddAttribute implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\View\Element\UiComponent\Context $context, \Magento\Framework\Registry $registry)
    {
        $this->___init();
        parent::__construct($context, $registry);
    }

    /**
     * {@inheritdoc}
     */
    public function getButtonData()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getButtonData');
        if (!$pluginInfo) {
            return parent::getButtonData();
        } else {
            return $this->___callPlugins('getButtonData', func_get_args(), $pluginInfo);
        }
    }
}
