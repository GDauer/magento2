<?php


namespace DigitalHub\SortingOptions\Plugin\Catalog\Model;


class Config
{
    public function afterGetAttributeUsedForSortByArray(
        \Magento\Catalog\Model\Config $catalogConfig,
        $options
    )
    {
        unset($options['name']);
        unset($options['price']);
        $options['position'] = __('Relevância');
        $options['price_desc'] = __('Maior Preço');
        $options['price_asc'] = __('Menor Preço');
        $options['name_asc'] = __('Nome (A - Z)');
        $options['name_desc'] = __('Nome (Z - A)');
        return $options;
    }
}