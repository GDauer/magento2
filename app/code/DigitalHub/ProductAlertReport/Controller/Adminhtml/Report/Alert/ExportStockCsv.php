<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace DigitalHub\ProductAlertReport\Controller\Adminhtml\Report\Alert;

use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\Filesystem\DirectoryList;

class ExportStockCsv extends \DigitalHub\ProductAlertReport\Controller\Adminhtml\Report\ProductAlert
{
    /**
     * Export shipping report to CSV format
     *
     * @return ResponseInterface
     */
    public function execute()
    {
        $this->_view->loadLayout(false);
        $fileName = 'product_alert_stock.csv';
        $grid = $this->_view->getLayout()->createBlock(\DigitalHub\ProductAlertReport\Block\Adminhtml\Alert\Stock\Grid::class);
        $this->_initReportAction($grid);
        return $this->_fileFactory->create($fileName, $grid->getCsvFile(), DirectoryList::VAR_DIR);
    }
}
