<?php
namespace Adyen\Payment\Gateway\Request\CcAuthorizationDataBuilder;

/**
 * Interceptor class for @see \Adyen\Payment\Gateway\Request\CcAuthorizationDataBuilder
 */
class Interceptor extends \Adyen\Payment\Gateway\Request\CcAuthorizationDataBuilder implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct()
    {
        $this->___init();
    }

    /**
     * {@inheritdoc}
     */
    public function build(array $buildSubject)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'build');
        if (!$pluginInfo) {
            return parent::build($buildSubject);
        } else {
            return $this->___callPlugins('build', func_get_args(), $pluginInfo);
        }
    }
}
