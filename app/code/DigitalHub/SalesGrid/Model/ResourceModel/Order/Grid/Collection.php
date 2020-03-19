<?php

namespace DigitalHub\SalesGrid\Model\ResourceModel\Order\Grid;

/**
 * Class Collection
 * @package DigitalHub\SalesGrid\Model\ResourceModel\Order\Grid
 */
class Collection extends \Magento\Sales\Model\ResourceModel\Order\Grid\Collection
{
    /**
     * Overwritten method to force correct mapping for the main table fields
     * @return Collection|void
     */
    protected function _initSelect()
    {
        $this->addFilterToMap('created_at', 'main_table.created_at');
        $this->addFilterToMap('base_grand_total', 'main_table.base_grand_total');
        $this->addFilterToMap('grand_total', 'main_table.grand_total');
        $this->addFilterToMap('store_id', 'main_table.store_id');
        $this->addFilterToMap('store_name', 'main_table.store_name');
        $this->addFilterToMap('order_id', 'main_table.order_id');
        $this->addFilterToMap('order_increment_id', 'main_table.order_increment_id');
        $this->addFilterToMap('billing_name', 'main_table.billing_name');
        $this->addFilterToMap('billing_name', 'main_table.shipping_name');
        $this->addFilterToMap('status', 'main_table.status');

        parent::_initSelect();
    }
}