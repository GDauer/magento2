<?php
namespace Magento\Checkout\Block\Onepage\Link;

/**
 * Interceptor class for @see \Magento\Checkout\Block\Onepage\Link
 */
class Interceptor extends \Magento\Checkout\Block\Onepage\Link implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\View\Element\Template\Context $context, \Magento\Checkout\Model\Session $checkoutSession, \Magento\Checkout\Helper\Data $checkoutHelper, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $checkoutSession, $checkoutHelper, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getCheckoutUrl()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCheckoutUrl');
        if (!$pluginInfo) {
            return parent::getCheckoutUrl();
        } else {
            return $this->___callPlugins('getCheckoutUrl', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isPossibleOnepageCheckout()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isPossibleOnepageCheckout');
        if (!$pluginInfo) {
            return parent::isPossibleOnepageCheckout();
        } else {
            return $this->___callPlugins('isPossibleOnepageCheckout', func_get_args(), $pluginInfo);
        }
    }
}
