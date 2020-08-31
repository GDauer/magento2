<?php
namespace Mageants\FreeGift\Controller\Index\AddCoupon;

/**
 * Interceptor class for @see \Mageants\FreeGift\Controller\Index\AddCoupon
 */
class Interceptor extends \Mageants\FreeGift\Controller\Index\AddCoupon implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\SalesRule\Model\Rule $rule, \Magento\Checkout\Model\Session $checkoutSession, \Magento\Catalog\Model\ProductRepository $productRepository, \Magento\SalesRule\Model\CouponFactory $couponFactory, \Magento\Checkout\Model\Cart $cart, \Magento\Framework\Serialize\Serializer\Json $serializer = null)
    {
        $this->___init();
        parent::__construct($context, $storeManager, $rule, $checkoutSession, $productRepository, $couponFactory, $cart, $serializer);
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
