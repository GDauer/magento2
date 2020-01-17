<?php

namespace DigitalHub\SalesGrid\Plugin;

class AddRegionIdToOrdersGridPlugin
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * AddDataToOrdersGrid constructor.
     *
     * @param \Psr\Log\LoggerInterface $customLogger
     * @param array $data
     */
    public function __construct(
        \Psr\Log\LoggerInterface $customLogger,
        array $data = []
    ) {
        $this->logger   = $customLogger;
    }

    /**
     * @param \Magento\Framework\View\Element\UiComponent\DataProvider\CollectionFactory $subject
     * @param \Magento\Sales\Model\ResourceModel\Order\Grid\Collection $collection
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
                $orderAddressTableName = $resource->getTable('sales_order_address');
                $collection->getSelect()->joinLeft(
                    ['soa' => $orderAddressTableName],
                    'soa.parent_id = main_table.entity_id AND soa.address_type = \'shipping\'',
                    ['region', 'city']
                );

                $salesOrderItemTableName = $resource->getTable('sales_order_item');
                $collection->getSelect()->joinLeft(
                    ['soi' => $salesOrderItemTableName],
                    'soi.order_id = main_table.entity_id',
                    [
                        'products' => new \Zend_Db_Expr('group_concat(`soi`.sku)'),
                        'products_id' => new \Zend_Db_Expr('group_concat(`soi`.product_id)')
                    ]
                )->group('main_table.entity_id');
            } catch (\Zend_Db_Select_Exception $selectException) {
                $this->logger->log(100, $selectException);
            }
        }

        return $collection;
    }
}