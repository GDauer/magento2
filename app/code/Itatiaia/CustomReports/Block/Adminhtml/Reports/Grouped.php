<?php
namespace Itatiaia\CustomReports\Block\Adminhtml\Reports;

class Grouped extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * @var string
     */
    protected $_blockGroup = 'Itatiaia_CustomReports';

    /**
     * Initialize container block settings
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_blockGroup = 'Itatiaia_CustomReports';
        $this->_controller = 'adminhtml_reports_grouped';
        $this->_headerText = __('Produtos agrupados vendidos');
        parent::_construct();
        $this->buttonList->remove('add');
    }
}
