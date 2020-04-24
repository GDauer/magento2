<?php

namespace DigitalHub\SortingOptions\Model\Product\Indexer\Fulltext\Datasource;

use Smile\ElasticsuiteCore\Api\Index\DatasourceInterface;

/**
 * Class SortingData
 * @package DigitalHub\SortingOptions\Model\Product\Indexer\Fulltext\Datasource
 */
class SortingData implements DatasourceInterface
{
    /**
     * Add data
     * @param int $storeId
     * @param array $indexData
     * @return array
     */
    public function addData($storeId, array $indexData)
    {
        foreach ($indexData as $data) {
            if (!$data['price']) {
                continue;
            }
            $productId = $data["entity_id"];
            $indexData[$productId]['price'] = $this->calculateDiscount($data["price"]);
        }

        return $indexData;
    }

    /**
     * Calc and set the discount amount on the price structure
     * @param $prices
     * @return mixed
     */
    private function calculateDiscount($prices)
    {
        foreach ($prices as $key => $item) {
            if (!isset($item['is_discount']) || !$item['is_discount']) {
                $prices[$key]['discount_percentage_amount'] = 0;
                continue;
            }

            $price = $item["price"];
            $originalPrice = $item["original_price"];

            $prices[$key]['discount_percentage_amount'] = (double)1 - ($price / $originalPrice);
        }

        return $prices;
    }
}