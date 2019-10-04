<?php

namespace Itatiaia\KondutoCustom\Plugin\Sales\Order;

use Magento\Sales\Model\ResourceModel\Order\Grid\Collection as SalesOrderGridCollection;

class GridPlugin
{
    private $collection;

    public function __construct(SalesOrderGridCollection $collection)
    {
        $this->collection = $collection;
    }

    public function aroundGetReport(
        \Magento\Framework\View\Element\UiComponent\DataProvider\CollectionFactory $subject,
        \Closure $proceed,
        $requestName
    )
    {
        $result = $proceed($requestName);
//        if ($requestName == 'sales_order_grid_data_source' && $result instanceof $this->collection) {
//            $select = $this->collection->getSelect();
//            $select->joinLeft(
//                ["konduto" => $this->collection->getTable("table_name")],
//                'main_table.increment_id = secondTable.order_id',
//                array('custom_column')
//            );
//            return $this->collection;
//        }
        return $result;
    }
}