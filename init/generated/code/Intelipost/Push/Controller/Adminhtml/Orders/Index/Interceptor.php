<?php
namespace Intelipost\Push\Controller\Adminhtml\Orders\Index;

/**
 * Interceptor class for @see \Intelipost\Push\Controller\Adminhtml\Orders\Index
 */
class Interceptor extends \Intelipost\Push\Controller\Adminhtml\Orders\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Framework\Registry $coreRegistry, \Magento\Ui\Component\MassAction\Filter $filter, \Intelipost\Quote\Model\Resource\Shipment\CollectionFactory $_collectionFactory, \Intelipost\Quote\Model\Shipment $shipment, \Intelipost\Push\Model\Request\ShipmentOrderFactory $shipmentOrder, \Intelipost\Push\Model\Request\ShippedFactory $shipped, \Intelipost\Push\Helper\Data $helper)
    {
        $this->___init();
        parent::__construct($context, $resultForwardFactory, $resultPageFactory, $coreRegistry, $filter, $_collectionFactory, $shipment, $shipmentOrder, $shipped, $helper);
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
