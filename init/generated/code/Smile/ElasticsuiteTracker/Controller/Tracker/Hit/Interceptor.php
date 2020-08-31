<?php
namespace Smile\ElasticsuiteTracker\Controller\Tracker\Hit;

/**
 * Interceptor class for @see \Smile\ElasticsuiteTracker\Controller\Tracker\Hit
 */
class Interceptor extends \Smile\ElasticsuiteTracker\Controller\Tracker\Hit implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Smile\ElasticsuiteTracker\Api\CustomerTrackingServiceInterface $trackingService)
    {
        $this->___init();
        parent::__construct($context, $trackingService);
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
