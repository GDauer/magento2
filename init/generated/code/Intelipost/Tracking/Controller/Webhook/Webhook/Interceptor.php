<?php
namespace Intelipost\Tracking\Controller\Webhook\Webhook;

/**
 * Interceptor class for @see \Intelipost\Tracking\Controller\Webhook\Webhook
 */
class Interceptor extends \Intelipost\Tracking\Controller\Webhook\Webhook implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Intelipost\Tracking\Helper\Data $helper, \Magento\Backend\App\Action\Context $context, \Intelipost\Quote\Model\Resource\Shipment\CollectionFactory $collectionFactory, \Intelipost\Quote\Model\Shipment $shipment, \Magento\Sales\Model\Order $order, \Magento\Sales\Model\Convert\Order $convertOrder, \Magento\Sales\Model\Order\Shipment\TrackFactory $track)
    {
        $this->___init();
        parent::__construct($scopeConfig, $helper, $context, $collectionFactory, $shipment, $order, $convertOrder, $track);
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
