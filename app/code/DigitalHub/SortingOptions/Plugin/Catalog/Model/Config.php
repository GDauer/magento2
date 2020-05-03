<?php


namespace DigitalHub\SortingOptions\Plugin\Catalog\Model;


class Config
{
    public function afterGetAttributeUsedForSortByArray(
        \Magento\Catalog\Model\Config $catalogConfig,
        $options
    ) {
        unset($options['name']);
        unset($options['price']);
        $options['position'] = __('Relevance');
        $options['sell_desc'] = __('Best Sellers');
        $options['price_desc'] = __('Maximal Price');
        $options['price_asc'] = __('Minimal Price');
        $options['name_asc'] = __('Nome (A - Z)');
        $options['name_desc'] = __('Nome (Z - A)');
        $options['discount_percentage_amount'] = __('Highest Discount');
        return $options;
    }
}