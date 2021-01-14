<?php


namespace DigitalHub\CouponReport\Block\Adminhtml\Report\CouponReport;


use Magento\Backend\Block\Template\Context;
use Magento\Backend\Helper\Data;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;

class Grid extends \Magento\Backend\Block\Widget\Grid
{
    private $factory;

    public function __construct(Context $context,
                                Data $backendHelper,
                                CollectionFactory $factory,
                                array $data = [])
    {
        $this->factory = $factory;
        parent::__construct($context, $backendHelper, $data);
    }

    protected function _prepareCollection()
    {
        $collection = $this->factory->create();
        $collection->getSelect()->where('coupon_code IS NOT NULL');

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
}