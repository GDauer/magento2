<?php
namespace Magento\Checkout\Block\Cart;

/**
 * Interceptor class for @see \Magento\Checkout\Block\Cart
 */
class Interceptor extends \Magento\Checkout\Block\Cart implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\View\Element\Template\Context $context, \Magento\Customer\Model\Session $customerSession, \Magento\Checkout\Model\Session $checkoutSession, \Magento\Catalog\Model\ResourceModel\Url $catalogUrlBuilder, \Magento\Checkout\Helper\Cart $cartHelper, \Magento\Framework\App\Http\Context $httpContext, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $customerSession, $checkoutSession, $catalogUrlBuilder, $cartHelper, $httpContext, $data);
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
}
