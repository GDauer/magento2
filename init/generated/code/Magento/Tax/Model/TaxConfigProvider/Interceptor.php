<?php
namespace Magento\Tax\Model\TaxConfigProvider;

/**
 * Interceptor class for @see \Magento\Tax\Model\TaxConfigProvider
 */
class Interceptor extends \Magento\Tax\Model\TaxConfigProvider implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Tax\Helper\Data $taxHelper, \Magento\Tax\Model\Config $taxConfig, \Magento\Checkout\Model\Session $checkoutSession, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->___init();
        parent::__construct($taxHelper, $taxConfig, $checkoutSession, $scopeConfig);
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getConfig');
        if (!$pluginInfo) {
            return parent::getConfig();
        } else {
            return $this->___callPlugins('getConfig', func_get_args(), $pluginInfo);
        }
    }
}
