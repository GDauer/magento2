<?php
namespace Magento\Customer\Model\Vat;

/**
 * Interceptor class for @see \Magento\Customer\Model\Vat
 */
class Interceptor extends \Magento\Customer\Model\Vat implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Psr\Log\LoggerInterface $logger)
    {
        $this->___init();
        parent::__construct($scopeConfig, $logger);
    }

    /**
     * {@inheritdoc}
     */
    public function checkVatNumber($countryCode, $vatNumber, $requesterCountryCode = '', $requesterVatNumber = '')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'checkVatNumber');
        if (!$pluginInfo) {
            return parent::checkVatNumber($countryCode, $vatNumber, $requesterCountryCode, $requesterVatNumber);
        } else {
            return $this->___callPlugins('checkVatNumber', func_get_args(), $pluginInfo);
        }
    }
}
