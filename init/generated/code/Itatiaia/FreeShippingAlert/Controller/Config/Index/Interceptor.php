<?php
namespace Itatiaia\FreeShippingAlert\Controller\Config\Index;

/**
 * Interceptor class for @see \Itatiaia\FreeShippingAlert\Controller\Config\Index
 */
class Interceptor extends \Itatiaia\FreeShippingAlert\Controller\Config\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \Magento\Checkout\Model\Session $checkoutSession, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Psr\Log\LoggerInterface $logger)
    {
        $this->___init();
        parent::__construct($context, $resultJsonFactory, $checkoutSession, $scopeConfig, $logger);
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
