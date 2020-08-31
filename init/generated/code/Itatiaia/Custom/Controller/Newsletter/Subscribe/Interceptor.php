<?php
namespace Itatiaia\Custom\Controller\Newsletter\Subscribe;

/**
 * Interceptor class for @see \Itatiaia\Custom\Controller\Newsletter\Subscribe
 */
class Interceptor extends \Itatiaia\Custom\Controller\Newsletter\Subscribe implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory, \Psr\Log\LoggerInterface $logger)
    {
        $this->___init();
        parent::__construct($context, $resultJsonFactory, $subscriberFactory, $logger);
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
