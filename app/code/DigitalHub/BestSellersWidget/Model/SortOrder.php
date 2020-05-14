<?php


namespace DigitalHub\BestSellersWidget\Model;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class SortOrder
 * @package DigitalHub\BestSellersWidget\Model
 */
class SortOrder implements ArrayInterface
{
    /**
     * Return options as array
     * @return array|array[]
     */
    public function toOptionArray()
    {
        return [
            ['value'=>'desc', 'label'=> __('Descending')],
            ['value'=>'asc', 'label'=> __('Ascending')]
        ];
    }
}