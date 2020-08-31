<?php
namespace Magento\Checkout\Model\Session\SuccessValidator;

/**
 * Interceptor class for @see \Magento\Checkout\Model\Session\SuccessValidator
 */
class Interceptor extends \Magento\Checkout\Model\Session\SuccessValidator implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Checkout\Model\Session $checkoutSession)
    {
        $this->___init();
        parent::__construct($checkoutSession);
    }

    /**
     * {@inheritdoc}
     */
    public function isValid()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isValid');
        if (!$pluginInfo) {
            return parent::isValid();
        } else {
            return $this->___callPlugins('isValid', func_get_args(), $pluginInfo);
        }
    }
}
