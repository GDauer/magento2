<?php
namespace Adyen\Payment\Controller\Process\Json;

/**
 * Interceptor class for @see \Adyen\Payment\Controller\Process\Json
 */
class Interceptor extends \Adyen\Payment\Controller\Process\Json implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Adyen\Payment\Helper\Data $adyenHelper, \Adyen\Payment\Logger\AdyenLogger $adyenLogger)
    {
        $this->___init();
        parent::__construct($context, $adyenHelper, $adyenLogger);
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
