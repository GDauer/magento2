<?php


namespace DigitalHub\RecentlyViewedWidget\Model;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class SortBy
 * @package DigitalHub\BestSellersWidget\Model
 */
class SortBy implements ArrayInterface
{
    /**
     * Return options as array
     * @return array|array[]
     */
    public function toOptionArray()
    {
        return [
            ['value'=>'id', 'label'=> __('Product Id')],
            ['value'=>'name', 'label'=> __('Product Name')],
            ['value'=>'price', 'label'=> __('Price')]
        ];
    }
}