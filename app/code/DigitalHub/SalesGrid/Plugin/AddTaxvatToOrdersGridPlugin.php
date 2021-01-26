<?php

namespace DigitalHub\SalesGrid\Plugin;

use Psr\Log\LoggerInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\CollectionFactory;
use Magento\Sales\Model\ResourceModel\Order\Grid\Collection;

class AddTaxvatToOrdersGridPlugin
{
    /** @var LoggerInterface  */
    private $logger;

    /**
     * AddTaxvatToOrdersGridPlugin constructor.
     * @param LoggerInterface $customLogger
     * @param array $data
     */
    public function __construct(
        LoggerInterface $customLogger,
        array $data = []
    ) {
        $this->logger = $customLogger;
    }

    /**
     * @param CollectionFactory $subject
     * @param Collection $collection
     * @param $requestName
     * @return mixed
     */
    public function afterGetReport($subject, $collection, $requestName)
    {
        if ($requestName !== 'sales_order_grid_data_source') {
            return $collection;
        }
        $resource = $collection->getResource();

        if ($collection->getMainTable() === $resource->getTable('sales_order_grid')) {
            try {
                $collection->addFilterToMap('created_at', 'main_table.created_at');
                $collection->addFilterToMap('base_grand_total', 'main_table.base_grand_total');
                $collection->addFilterToMap('grand_total', 'main_table.grand_total');
                $collection->addFilterToMap('store_id', 'main_table.store_id');
                $collection->addFilterToMap('store_name', 'main_table.store_name');
                $collection->addFilterToMap('order_id', 'main_table.order_id');
                $collection->addFilterToMap('order_increment_id', 'main_table.order_increment_id');
                $collection->addFilterToMap('billing_name', 'main_table.billing_name');
                $collection->addFilterToMap('billing_name', 'main_table.shipping_name');
                $collection->addFilterToMap('status', 'main_table.status');
                $collection->addFilterToMap('increment_id', 'main_table.increment_id');
                $collection->addFilterToMap('customer_email', 'main_table.customer_email');
                $collection->addFilterToMap('subtotal', 'main_table.subtotal');
                $collection->addFilterToMap('total_refunded', 'main_table.total_refunded');
                $salesOrderTableName = $resource->getTable('sales_order');
                $collection->getSelect()->joinLeft(
                    ['so' => $salesOrderTableName],
                    'so.increment_id = main_table.increment_id',
                    ['so.customer_taxvat']
                );
            } catch (\Zend_Db_Select_Exception $selectException) {
                $this->logger->log(100, $selectException);
            }
        }

        return $collection;
    }
}