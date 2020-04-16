<?php


namespace DigitalHub\SortingOptions\Plugin\Catalog\Block;


class Toolbar
{
    public function aroundSetCollection(
        \Magento\Catalog\Block\Product\ProductList\Toolbar $subject,
        \Closure $proceed,
        $collection
    )
    {
        $currentOrder = $subject->getCurrentOrder();
        $result = $proceed($collection);

        if ($currentOrder) {
            switch ($currentOrder) {
                case 'price_asc':
                    $subject->getCollection()->setOrder('price', 'asc');
                    break;
                case 'price_desc':
                    $subject->getCollection()->setOrder('price', 'desc');
                    break;
                case 'name_asc':
                    $subject->getCollection()->setOrder('name', 'asc');
                    break;
                case 'name_desc':
                    $subject->getCollection()->setOrder('name', 'desc');
                break;
                case 'descount_desc':
                    $collection = $subject->getCollection();
                    $select = $collection->getSelect();
                    $select->columns(array(
                        "(100 - ((price_index.final_price * 100) / price_index.price)) AS descount"
                    ));
                    $select->order("descount DESC");
                break;
                default:
                case 'sell_desc':
                    if (!$subject->getCollection()->getFlag("sell_desc_order")) {
                        $select = $subject->getCollection()->getSelect();
                        $select->joinLeft(
                            array("order_sell_item_count_table" => new \Zend_Db_Expr("(SELECT product_id, SUM(qty_ordered) AS vendas FROM sales_order_item GROUP BY product_id)")),
                            "order_sell_item_count_table.product_id = e.entity_id",
                            array("vendas" => "order_sell_item_count_table.vendas"));
                        $select->order("vendas DESC");
                        $subject->getCollection()->setFlag("sell_desc_order", true);
                        $subject->getCollection()->setFlag("ignore_elastic_score_apply", true);
                    }
                    break;
            }
        }

        return $result;
    }
}