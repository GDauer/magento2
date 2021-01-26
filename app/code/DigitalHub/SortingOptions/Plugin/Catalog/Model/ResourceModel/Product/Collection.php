<?php


namespace DigitalHub\SortingOptions\Plugin\Catalog\Model\ResourceModel\Product;

use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;
use Psr\Log\LoggerInterface;

class Collection
{
    protected $logger;

    /**
     * Collection constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Product Collection before load plugin to add in stock sort order
     * @param ProductCollection $subject
     * @param bool $printQuery
     * @param bool $logQuery
     * @return bool[]
     */
    public function beforeLoad(ProductCollection $subject, $printQuery = false, $logQuery = false)
    {
        if (!$subject->getFlag('in_stock_sort_flag')) {
            $subject->setFlag('in_stock_sort_flag', true);
            try {
                $subject->getSelect()->joinLeft(
                    ['_inventory_table' => $subject->getTable('cataloginventory_stock_item')],
                    "_inventory_table.product_id = e.entity_id",
                    ['is_in_stock', 'manage_stock']
                );

                $subject->addExpressionAttributeToSelect(
                    'on_top',
                    '(CASE WHEN (((_inventory_table.use_config_manage_stock = 1) 
                     AND (_inventory_table.is_in_stock = 1)) 
                     OR  ((_inventory_table.use_config_manage_stock = 0) 
                     AND (1 - _inventory_table.manage_stock + _inventory_table.is_in_stock >= 1))) THEN 1 ELSE 0 END)',
                    []
                );

                $subject->getSelect()->order('on_top DESC');
                $order = $subject->getSelect()->getPart('order');
                $subject->setOrder("stock.is_in_stock", "DESC");
                array_unshift($order, array_pop($order));
                $subject->getSelect()->setPart('order', $order);
            } catch (\Exception $e) {
                $this->logger->error(__("Error adding stock sort order on product collection. Error: %1",
                    $e->getMessage()));
            }
        }

        return [$printQuery, $logQuery];
    }
}