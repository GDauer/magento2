<?php
namespace Adyen\Payment\Controller\Process\ResultPos;

/**
 * Interceptor class for @see \Adyen\Payment\Controller\Process\ResultPos
 */
class Interceptor extends \Adyen\Payment\Controller\Process\ResultPos implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Adyen\Payment\Helper\Data $adyenHelper, \Magento\Sales\Model\OrderFactory $orderFactory, \Magento\Sales\Model\Order\Status\HistoryFactory $orderHistoryFactory, \Magento\Checkout\Model\Session $session, \Adyen\Payment\Logger\AdyenLogger $adyenLogger)
    {
        $this->___init();
        parent::__construct($context, $adyenHelper, $orderFactory, $orderHistoryFactory, $session, $adyenLogger);
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
