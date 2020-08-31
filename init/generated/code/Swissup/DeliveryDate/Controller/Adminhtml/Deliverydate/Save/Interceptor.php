<?php
namespace Swissup\DeliveryDate\Controller\Adminhtml\Deliverydate\Save;

/**
 * Interceptor class for @see \Swissup\DeliveryDate\Controller\Adminhtml\Deliverydate\Save
 */
class Interceptor extends \Swissup\DeliveryDate\Controller\Adminhtml\Deliverydate\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \Swissup\DeliveryDate\Model\DeliverydateFactory $deliverydateFactory, \Magento\Sales\Model\OrderFactory $orderFactory, \Swissup\DeliveryDate\Helper\Data $dataHelper)
    {
        $this->___init();
        parent::__construct($context, $resultJsonFactory, $deliverydateFactory, $orderFactory, $dataHelper);
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
