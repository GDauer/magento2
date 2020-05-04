<?php


namespace DigitalHub\SortingOptions\Model\ResourceModel\Product\Fulltext;

/**
 * Class CollectionOverwrite
 * @package DigitalHub\SortingOptions\Model\ResourceModel\Product\Fulltext
 */
class CollectionOverwrite extends \Smile\ElasticsuiteCatalog\Model\ResourceModel\Product\Fulltext\Collection
{
    /**
     * {@inheritDoc}
     */
    public function setOrder($attribute, $dir = self::SORT_ORDER_DESC)
    {
        if (!isset($this->_orders['stock.is_in_stock']) && $attribute !== 'stock.is_in_stock') {
            $this->_orders['stock.is_in_stock'] = 'desc';
        }

        $this->_orders[$attribute] = $dir;

        return $this;
    }
}