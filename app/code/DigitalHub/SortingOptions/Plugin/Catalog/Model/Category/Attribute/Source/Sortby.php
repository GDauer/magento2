<?php


namespace DigitalHub\SortingOptions\Plugin\Catalog\Model\Category\Attribute\Source;

use Magento\Catalog\Model\Category\Attribute\Source\SortBy as CategoryAttributeSortBy;
use DigitalHub\SortingOptions\Helper\DataTrait;

class Sortby
{
    use DataTrait;

    /**
     * Plugin to add the store sort by attributes on category
     * @param CategoryAttributeSortBy $subject
     * @param $result
     * @return array
     */
    public function afterGetAllOptions(CategoryAttributeSortBy $subject, $result)
    {
        $sortOptions = $this->getSortByOptionsWithLabel();
        $result = array_merge($sortOptions, $result);
        return $result;
    }
}