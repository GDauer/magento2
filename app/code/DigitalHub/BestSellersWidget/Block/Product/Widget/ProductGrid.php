<?php


namespace DigitalHub\BestSellersWidget\Block\Product\Widget;

use Magento\CatalogWidget\Block\Product\ProductsList;

/**
 * Class ProductGrid
 * @package DigitalHub\BestSellersWidget\Block\Product\Widget
 */
class ProductGrid extends ProductsList
{
    const DEFAULT_SORT_BY = 'best_seller';
    const DEFAULT_SORT_ORDER = 'desc';
    const SORT_BY_PARAM = 'products_sort_by';
    const SORT_ORDER_PARAM = 'products_sort_order';

    /**
     * Create Collection
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function createCollection()
    {
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToSelect('name');
        $collection->setVisibility($this->catalogProductVisibility->getVisibleInCatalogIds());

        $pageSize = $this->getPageSize();
        $currentPage = $this->getRequest()->getParam($this->getData('page_var_name'), 1);

        $collection = $this->_addProductAttributesAndPrices($collection)
            ->addStoreFilter()
            ->setPageSize($pageSize)
            ->setCurPage($currentPage)
            ->setOrder($this->getSortBy(), $this->getSortOrder());

        $collection->getSelect()->joinLeft(
            ["order_sell_item_count_table" => new \Zend_Db_Expr("
            (SELECT product_id, SUM(qty_ordered) AS best_seller FROM sales_order_item GROUP BY product_id)")],
            "order_sell_item_count_table.product_id = e.entity_id",
            ["best_seller" => "order_sell_item_count_table.best_seller"]);
        $collection->getSelect()->where('order_sell_item_count_table.best_seller >=1');

        if ($this->getSortBy() !== self::DEFAULT_SORT_BY) {
            return $collection;
        }

        $collection->getSelect()->order($this->getSortBy() . ' ' . $this->getSortOrder());

        try {
            $order = $collection->getSelect()->getPart('order');
            array_unshift($order, array_pop($order));
            $collection->getSelect()->setPart('order', $order);
        } catch (\Exception $e) {
            $this->_logger->error(__("Error adding best seller sort order on product collection. Error: %1",
                $e->getMessage()));
        }

        return $collection;
    }

    /**
     * Return Sort By
     * @return array|mixed|null
     */
    public function getSortBy()
    {
        if (!$this->hasData(self::SORT_BY_PARAM)) {
            $this->setData(self::SORT_BY_PARAM, self::DEFAULT_SORT_BY);
        }
        return $this->getData(self::SORT_BY_PARAM);
    }

    /**
     * Return Sort Order type
     * @return array|mixed|null
     */
    public function getSortOrder()
    {
        if (!$this->hasData(self::SORT_ORDER_PARAM)) {
            $this->setData(self::SORT_ORDER_PARAM, self::DEFAULT_SORT_ORDER);
        }
        return $this->getData(self::SORT_ORDER_PARAM);
    }
}