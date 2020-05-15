<?php

namespace DigitalHub\ProductAlertReport\Controller\Adminhtml\Report\Alert;

class Stock extends \DigitalHub\ProductAlertReport\Controller\Adminhtml\Report\ProductAlert
{
    public function execute()
    {
        $this->_initAction()->_setActiveMenu(
            'DigitalHub_ProductAlertReport::report_product_alert_stock'
        )->_addBreadcrumb(
            __('Alerta Produto/Estoque'),
            __('Alerta Produto/Estoque')
        );
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Relatório Alerta Produto/Estoque'));

        $gridBlock = $this->_view->getLayout()->getBlock('adminhtml_alert_stock.grid');
        $filterFormBlock = $this->_view->getLayout()->getBlock('grid.filter.form');

        $this->_initReportAction([$gridBlock, $filterFormBlock]);
        $this->_view->renderLayout();
    }
}