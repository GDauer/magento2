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

        $collection->getSelect()->joinLeft([
            "repo_viewed" => new \Zend_Db_Expr("
            (SELECT 
                index_id, product_id, NOW()-added_at AS elapsed_time 
                FROM report_viewed_product_index
                WHERE
		            index_id in (
                        select
                            max(index_id)
                        from
                            report_viewed_product_index
                        group by
                            product_id ) 
                GROUP BY product_id)")
        ],
            "repo_viewed.product_id = e.entity_id",
            ["index_id" => "repo_viewed.index_id", "elapsed_time" => "repo_viewed.elapsed_time"]);

        $collection->getSelect()->order('elapsed_time ASC');

        return $collection;
    }
}