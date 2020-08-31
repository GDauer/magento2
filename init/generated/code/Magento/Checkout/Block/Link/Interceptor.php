<?php
namespace Magento\Checkout\Block\Link;

/**
 * Interceptor class for @see \Magento\Checkout\Block\Link
 */
class Interceptor extends \Magento\Checkout\Block\Link implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\View\Element\Template\Context $context, \Magento\Framework\Module\Manager $moduleManager, \Magento\Checkout\Helper\Data $checkoutHelper, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $moduleManager, $checkoutHelper, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getHref()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getHref');
        if (!$pluginInfo) {
            return parent::getHref();
        } else {
            return $this->___callPlugins('getHref', func_get_args(), $pluginInfo);
        }
    }
}
