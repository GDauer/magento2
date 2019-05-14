<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Report Sold Grouped Products collection
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
namespace Itatiaia\CustomReports\Model\ResourceModel\Report;

use Magento\Framework\DB\Select;

class GroupedCollection extends \Magento\Sales\Model\ResourceModel\Order\Item\Collection
{

    /**
     * Set store filter to collection
     *
     * @param array $storeIds
     * @return $this
     */
    public function setStoreIds($storeIds)
    {
        if ($storeIds) {
            $this->getSelect()->where('main_table.store_id IN (?)', (array)$storeIds);
        }
        return $this;
    }

    /**
     * @return Select
     * @since 100.2.0
     */
    public function getSelectCountSql()
    {
        $countSelect = clone parent::getSelectCountSql();

        $countSelect->reset(Select::COLUMNS);
        $countSelect->columns('COUNT(*)');

        return $countSelect;
    }

    /**
     * Prepare between sql
     *
     * @param string $fieldName Field name with table suffix ('created_at' or 'main_table.created_at')
     * @param string $from
     * @param string $to
     * @return string Formatted sql string
     */
    protected function prepareBetweenSql($fieldName, $from, $to)
    {
        return sprintf(
            '(%s BETWEEN %s AND %s)',
            $fieldName,
            $this->getConnection()->quote($from),
            $this->getConnection()->quote($to)
        );
    }

    protected function _beforeLoad()
    {
        $this->addFieldToFilter('product_type', 'grouped');

        $this->getSelect()->reset(\Zend_Db_Select::COLUMNS);

        $this->getSelect()->columns([
            'total' => 'count(*)',
            'store_id' => 'main_table.store_id',
            'created_at' => 'main_table.created_at',
            'grouped_id' => new \Zend_Db_Expr ("reverse(substring(reverse(substring(REGEXP_SUBSTR(product_options, '\{\"product_type\"\:\"grouped\"\,\"product_id\"\:\"[0-9]+\"\}'), 41)), 3))"),
            'sku' => new \Zend_Db_Expr ("(SELECT sku FROM catalog_product_entity as cpe WHERE cpe.entity_id = grouped_id)")
        ]);

        $this->getSelect()->group('grouped_id');
        $this->getSelect()->order('total desc');

        return parent::_beforeLoad();
    }
}
