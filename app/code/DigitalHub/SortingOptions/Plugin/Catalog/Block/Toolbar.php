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
            }
        }

        return $result;
    }
}