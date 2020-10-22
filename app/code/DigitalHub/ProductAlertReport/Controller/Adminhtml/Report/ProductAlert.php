<?php

namespace DigitalHub\ProductAlertReport\Controller\Adminhtml\Report;

use Magento\Reports\Controller\Adminhtml\Report\AbstractReport;


abstract class ProductAlert extends AbstractReport
{
    /**
     * @return $this|AbstractReport
     */
    public function _initAction()
    {
        parent::_initAction();
        $this->_addBreadcrumb(__('Alerta Produto/Estoque'), __('Alerta Produto/Estoque'));
        return $this;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('DigitalHub_ProductAlertReport::report_product_alert_stock');
    }
}