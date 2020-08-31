<?php
namespace Adyen\Payment\Controller\Process\Redirect;

/**
 * Interceptor class for @see \Adyen\Payment\Controller\Process\Redirect
 */
class Interceptor extends \Adyen\Payment\Controller\Process\Redirect implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Adyen\Payment\Logger\AdyenLogger $adyenLogger, \Adyen\Payment\Helper\Data $adyenHelper, \Adyen\Payment\Model\Api\PaymentRequest $paymentRequest, \Magento\Sales\Api\OrderRepositoryInterface $orderRepository, \Magento\Vault\Api\Data\PaymentTokenFactoryInterface $paymentTokenFactory, \Magento\Sales\Api\Data\OrderPaymentExtensionInterfaceFactory $paymentExtensionFactory, \Magento\Sales\Model\ResourceModel\Order\Payment $orderPaymentResource)
    {
        $this->___init();
        parent::__construct($context, $adyenLogger, $adyenHelper, $paymentRequest, $orderRepository, $paymentTokenFactory, $paymentExtensionFactory, $orderPaymentResource);
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
