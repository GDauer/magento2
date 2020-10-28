<?php


namespace DigitalHub\RecentlyViewedWidget\Block\Product\Widget;

use Magento\CatalogWidget\Block\Product\ProductsList;

/**
 * Class ProductGrid
 * @package DigitalHub\RecentlyViewedWidget\Block\Product\Widget
 */
class ProductGrid extends ProductsList
{

    public function getCacheLifetime()
    {
        return 0;
    }

    public function getCacheKeyInfo()
    {
        return ['RV_WIDGET', uniqid()];
    }

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
            ->setCurPage($currentPage);

        $collection->getSelect()->joinInner(
            ["repo_viewed" => new \Zend_Db_Expr("
                (
                    SELECT MAX(index_id) AS product_index_id, product_id, MIN(TIMESTAMPDIFF(SECOND, added_at, NOW())) AS elapsed_time 
                    FROM report_viewed_product_index 
                    GROUP BY product_id
                )"
            )],
            "repo_viewed.product_id = e.entity_id AND repo_viewed.elapsed_time IS NOT NULL",
            ["index_id" => "repo_viewed.product_index_id", "elapsed_time" => "repo_viewed.elapsed_time"]
        );

        $collection->getSelect()->order('elapsed_time ASC');

        return $collection;
    }

    private function getSubQuery()
    {
        $subQuery = new Zend_Db_Expr("( 
            SELECT DISTINCT(product_id), category_id 
            FROM `catalog_category_product` 
            GROUP BY product_id
        )");
    }
}