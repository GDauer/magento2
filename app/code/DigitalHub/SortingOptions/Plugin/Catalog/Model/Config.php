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
        unset($options['position']);
        $options['sell_desc'] = __('Mais Vendidos');
        $options['price_desc'] = __('Maior Preço');
        $options['price_asc'] = __('Menor Preço');
        $options['name_asc'] = __('Nome (A - Z)');
        $options['name_desc'] = __('Nome (Z - A)');
        $options['discount_percentage_amount'] = __('Maior Desconto');
        return $options;
    }
}