<?php


namespace DigitalHub\CouponReport\Block\Adminhtml\Report;


use Magento\Backend\Block\Widget\Grid\Container;

class CouponReport extends Container
{
    /**
     * @var string
     */
    protected $_blockGroup = 'DigitalHub_CouponReport';

    /**
     * Initialize container block settings
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_blockGroup = 'DigitalHub_CouponReport';
        $this->_controller = 'adminhtml_report';
        $this->_headerText = __('Pedidos por Cupom');
        parent::_construct();
        $this->buttonList->remove('add');
    }
}