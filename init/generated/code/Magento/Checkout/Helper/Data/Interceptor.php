<?php
namespace Magento\Checkout\Helper\Data;

/**
 * Interceptor class for @see \Magento\Checkout\Helper\Data
 */
class Interceptor extends \Magento\Checkout\Helper\Data implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Helper\Context $context, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Checkout\Model\Session $checkoutSession, \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate, \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder, \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation, \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency, \Magento\Sales\Api\PaymentFailuresInterface $paymentFailures = null)
    {
        $this->___init();
        parent::__construct($context, $storeManager, $checkoutSession, $localeDate, $transportBuilder, $inlineTranslation, $priceCurrency, $paymentFailures);
    }

    /**
     * {@inheritdoc}
     */
    public function canOnepageCheckout()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'canOnepageCheckout');
        if (!$pluginInfo) {
            return parent::canOnepageCheckout();
        } else {
            return $this->___callPlugins('canOnepageCheckout', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isDisplayBillingOnPaymentMethodAvailable()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isDisplayBillingOnPaymentMethodAvailable');
        if (!$pluginInfo) {
            return parent::isDisplayBillingOnPaymentMethodAvailable();
        } else {
            return $this->___callPlugins('isDisplayBillingOnPaymentMethodAvailable', func_get_args(), $pluginInfo);
        }
    }
}
