<?php


namespace DigitalHub\SortingOptions\Plugin\Catalog\Block;


class Toolbar
{
    public function aroundSetCollection(
        \Magento\Catalog\Block\Product\ProductList\Toolbar $subject,
        \Closure $proceed,
        $collection
    ) {
        $currentOrder = $subject->getCurrentOrder();
        $result = $proceed($collection);

        if ($currentOrder) {
            $subject->getCollection()->setOrder("stock.is_in_stock", "DESC");
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
                case 'discount_percentage_amount':
                    if (!$subject->getCollection()->getFlag("discount_desc_order")) {
                        $subject->getCollection()->getSelect()->joinLeft(
                            ['related' => 'catalog_product_relation'], 'related.parent_id = e.entity_id', [])
                            ->joinLeft(
                                ['related_price' => 'catalog_product_index_price'],
                                'related.child_id = related_price.entity_id and price_index.website_id = related_price.website_id '
                                . 'AND price_index.customer_group_id = related_price.customer_group_id ',
                                []
                            )
                            ->joinLeft(
                                ['crp' => 'catalogrule_product'],
                                'crp.product_id = e.entity_id and crp.customer_group_id = price_index.customer_group_id and crp.website_id = price_index.website_id',
                                ['action_operator', 'action_amount']
                            )
                            ->columns([
                                'discount_percentage_amount' => new \Zend_Db_Expr(
                                    'GREATEST(
                                    COALESCE(((price_index.price - price_index.final_price) / price_index.price),0), 
                                    COALESCE(((related_price.price - related_price.final_price) / related_price.price), 0),
                                    COALESCE(crp.action_amount/100, 0))'),
                            ])
                            ->group(['e.entity_id']);

                        $subject->getCollection()->getSelect()->distinct(true);
                        $subject->getCollection()->setOrder("price.discount_percentage_amount", "DESC");
                        $subject->getCollection()->getSelect()->order("discount_percentage_amount DESC");
                        $subject->getCollection()->setOrder($currentOrder, "desc");
                        $subject->getCollection()->getSelect()->limit($result->getLimit());
                        $subject->getCollection()->setFlag("discount_desc_order", true);

                    }
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
                    }
                    break;
            }
        }

        return $result;
    }
}