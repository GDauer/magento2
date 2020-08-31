<?php
namespace Temando\Shipping\Block\Adminhtml\Rma\RmaShipment\View;

/**
 * Interceptor class for @see \Temando\Shipping\Block\Adminhtml\Rma\RmaShipment\View
 */
class Interceptor extends \Temando\Shipping\Block\Adminhtml\Rma\RmaShipment\View implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\Block\Widget\Context $context, \Temando\Shipping\Model\ResourceModel\Rma\RmaAccess $rmaAccess, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $rmaAccess, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function canRender(\Magento\Backend\Block\Widget\Button\Item $item)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'canRender');
        if (!$pluginInfo) {
            return parent::canRender($item);
        } else {
            return $this->___callPlugins('canRender', func_get_args(), $pluginInfo);
        }
    }
}
