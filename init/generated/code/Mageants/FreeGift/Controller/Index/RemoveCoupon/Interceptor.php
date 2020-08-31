<?php
namespace Mageants\FreeGift\Controller\Index\RemoveCoupon;

/**
 * Interceptor class for @see \Mageants\FreeGift\Controller\Index\RemoveCoupon
 */
class Interceptor extends \Mageants\FreeGift\Controller\Index\RemoveCoupon implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\SalesRule\Model\Rule $rule, \Magento\Checkout\Model\Session $checkoutSession, \Magento\SalesRule\Model\CouponFactory $couponFactory, \Magento\Checkout\Model\Cart $cart, \Magento\Quote\Api\CartRepositoryInterface $quoteRepository, \Magento\Framework\Serialize\Serializer\Json $serializer = null)
    {
        $this->___init();
        parent::__construct($context, $rule, $checkoutSession, $couponFactory, $cart, $quoteRepository, $serializer);
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
