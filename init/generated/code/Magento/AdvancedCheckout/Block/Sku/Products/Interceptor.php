<?php
namespace Magento\AdvancedCheckout\Block\Sku\Products;

/**
 * Interceptor class for @see \Magento\AdvancedCheckout\Block\Sku\Products
 */
class Interceptor extends \Magento\AdvancedCheckout\Block\Sku\Products implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\View\Element\Template\Context $context, \Magento\Customer\Model\Session $customerSession, \Magento\Checkout\Model\Session $checkoutSession, \Magento\Catalog\Model\ResourceModel\Url $catalogUrlBuilder, \Magento\Checkout\Helper\Cart $cartHelper, \Magento\Framework\App\Http\Context $httpContext, \Magento\AdvancedCheckout\Model\Cart $cart, \Magento\AdvancedCheckout\Helper\Data $checkoutData, \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry, \Magento\Framework\Url\EncoderInterface $urlEncoder, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $customerSession, $checkoutSession, $catalogUrlBuilder, $cartHelper, $httpContext, $cart, $checkoutData, $stockRegistry, $urlEncoder, $data);
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
