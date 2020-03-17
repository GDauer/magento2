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