<?php
namespace Adyen\Payment\Helper\Requests;

/**
 * Interceptor class for @see \Adyen\Payment\Helper\Requests
 */
class Interceptor extends \Adyen\Payment\Helper\Requests implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Adyen\Payment\Helper\Data $adyenHelper)
    {
        $this->___init();
        parent::__construct($adyenHelper);
    }

    /**
     * {@inheritdoc}
     */
    public function buildCustomerIpData($request, $ipAddress)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'buildCustomerIpData');
        if (!$pluginInfo) {
            return parent::buildCustomerIpData($request, $ipAddress);
        } else {
            return $this->___callPlugins('buildCustomerIpData', func_get_args(), $pluginInfo);
        }
    }
}
