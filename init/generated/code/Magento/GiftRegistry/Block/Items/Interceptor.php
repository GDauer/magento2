<?php
namespace Magento\GiftRegistry\Block\Items;

/**
 * Interceptor class for @see \Magento\GiftRegistry\Block\Items
 */
class Interceptor extends \Magento\GiftRegistry\Block\Items implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\View\Element\Template\Context $context, \Magento\Customer\Model\Session $customerSession, \Magento\Checkout\Model\Session $checkoutSession, \Magento\Catalog\Model\ResourceModel\Url $catalogUrlBuilder, \Magento\Checkout\Helper\Cart $cartHelper, \Magento\Framework\App\Http\Context $httpContext, \Magento\GiftRegistry\Model\ItemFactory $itemFactory, \Magento\Quote\Model\QuoteFactory $quoteFactory, \Magento\Quote\Model\Quote\ItemFactory $quoteItemFactory, \Magento\Framework\Registry $registry, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $customerSession, $checkoutSession, $catalogUrlBuilder, $cartHelper, $httpContext, $itemFactory, $quoteFactory, $quoteItemFactory, $registry, $data);
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
