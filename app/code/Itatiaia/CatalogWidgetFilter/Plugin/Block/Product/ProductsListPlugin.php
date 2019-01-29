<?php
namespace Itatiaia\CatalogWidgetFilter\Plugin\Block\Product;

class ProductsListPlugin
{
    public function afterCreateCollection(\Magento\CatalogWidget\Block\Product\ProductsList $subject, $result)
    {
        $collection = $result;

        $collection->joinField('stock_status', 'cataloginventory_stock_status', 'stock_status', 'product_id=entity_id', '{{table}}.stock_id=1', 'left')
            ->addFieldToFilter('stock_status', array(
                    'eq' => \Magento\CatalogInventory\Model\Stock\Status::STATUS_IN_STOCK
                ));

        return $collection;
    }
}
