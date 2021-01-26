<?php

namespace DigitalHub\ProductAlertReport\Block\Adminhtml\Alert;

class Stock extends \Magento\Backend\Block\Widget\Grid\Container
{
    protected $_template = 'DigitalHub_ProductAlertReport::report/grid/container.phtml';


    protected function _construct()
    {
        $this->_blockGroup = 'DigitalHub_ProductAlertReport';
        $this->_controller = 'adminhtml_alert_stock';
        $this->_headerText = __('Relatório de Alerta Produto/Estoque');
        parent::_construct();

        $this->buttonList->remove('add');
    }

    public function getFilterUrl()
    {
        $this->getRequest()->setParam('filter', null);
        return $this->getUrl('*/*/stock', ['_current' => true]);
    }
}