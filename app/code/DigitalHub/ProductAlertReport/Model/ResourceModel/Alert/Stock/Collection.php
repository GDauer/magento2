<?php


namespace DigitalHub\ProductAlertReport\Model\ResourceModel\Alert\Stock;

use Magento\Framework\App\ResourceConnection;

class Collection extends \Magento\Sales\Model\ResourceModel\Report\Collection\AbstractCollection
{
    /**
     * Collection constructor.
     * @param \Magento\Framework\Data\Collection\EntityFactory $entityFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Magento\Sales\Model\ResourceModel\Report $resource
     * @param \Magento\Framework\DB\Adapter\AdapterInterface|null $connection
     */
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactory $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Sales\Model\ResourceModel\Report $resource,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null
    )
    {
        $resource->init('product_alert_stock');
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $resource, $connection);
    }


    /**
     * Get SQL for get record count
     *
     * @return \Magento\Framework\DB\Select
     */
    public function getSelectCountSql()
    {
        $this->_renderFilters();
        $select = clone $this->getSelect();
        $select->reset(\Magento\Framework\DB\Select::ORDER);
        return $this->getConnection()->select()->from($select, 'COUNT(*)');
    }

    /**
     * @return $this|\Magento\Sales\Model\ResourceModel\Report\Collection\AbstractCollection
     */
    protected function _beforeLoad()
    {
        $this->getSelect()
            ->from('product_alert_stock', ['alert_stock_id','add_date', 'send_date', 'send_count', 'status']);
        $this->getSelect()
            ->join(
                ['ce' => $this->getResource()->getTable('customer_entity')],
                'customer_id = ce.entity_id',
                ['ce.email']
            );
        $this->getSelect()
            ->join(
                ['cpe' => $this->getResource()->getTable('catalog_product_entity')],
                'product_id = cpe.entity_id',
                ['cpe.sku']
            )->order('add_date DESC');

        return $this;
    }
}