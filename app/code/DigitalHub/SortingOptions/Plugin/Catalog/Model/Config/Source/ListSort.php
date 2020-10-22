<?php


namespace DigitalHub\SortingOptions\Plugin\Catalog\Model\Config\Source;

use DigitalHub\SortingOptions\Helper\DataTrait;
use Magento\Catalog\Model\Config\Source\ListSort as CatalogListSort;

class ListSort
{
    use DataTrait;

    /**
     * Plugin to add the store sort by attributes
     * @param CatalogListSort $subject
     * @param $result
     * @return array
     */
    public function afterToOptionArray(CatalogListSort $subject, $result)
    {
        $sortOptions = $this->getSortByOptionsWithLabel();
        $result = array_merge($sortOptions, $result);
        return $result;
    }
}