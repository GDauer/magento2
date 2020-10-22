<?php


namespace DigitalHub\SortingOptions\Helper;

/**
 * Trait DataTrait
 * @package DigitalHub\SortingOptions\Helper
 */
trait DataTrait
{
    /**
     * Return the sort by options with translated label
     * @return array[]
     */
    public function getSortByOptionsWithLabel()
    {
        return [
            ['label' => __('Best Sellers'), 'value' => 'sell_desc'],
            ['label' => __('Maximal Price'), 'value' => 'price_desc'],
            ['label' => __('Minimal Price'), 'value' => 'price_asc'],
            ['label' => __('Name (A - Z)'), 'value' => 'name_asc'],
            ['label' => __('Name (Z - A)'), 'value' => 'name_desc'],
            ['label' => __('Highest Discount'), 'value' => 'discount_percentage_amount'],
        ];
    }
}