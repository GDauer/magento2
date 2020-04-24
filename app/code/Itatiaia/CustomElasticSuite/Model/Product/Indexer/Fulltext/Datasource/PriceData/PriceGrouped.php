<?php

namespace Itatiaia\CustomElasticSuite\Model\Product\Indexer\Fulltext\Datasource\PriceData;

use Magento\Catalog\Model\ProductFactory;
use Itatiaia\GroupedCatalogRule\Helper\Data;

// Customização para colocar preço default para produtos agrupados na indexação (ajuste no filtro com valor min-max)

class PriceGrouped extends \Smile\ElasticsuiteCatalog\Model\Product\Indexer\Fulltext\Datasource\PriceData\PriceGrouped
{
    /** @var ProductFactory */
    protected $productFactory;

    /** @var Data */
    protected $groupedCatalogRuleHelper;

    /**
     * PriceGrouped constructor.
     * @param ProductFactory $productFactory
     * @param Data $groupedCatalogRuleHelper
     */
    public function __construct(ProductFactory $productFactory, Data $groupedCatalogRuleHelper)
    {
        $this->productFactory = $productFactory;
        $this->groupedCatalogRuleHelper = $groupedCatalogRuleHelper;
    }

    /**
     * {@inheritDoc}
     */
    public function getPrice($priceData)
    {
        $product = $this->getProduct($priceData['entity_id']);
        if ($product->getTypeId() == 'grouped') {
            return $this->groupedCatalogRuleHelper->getFinalPrice($product);
        }
        return $product->getFinalPrice();
    }

    /**
     * {@inheritDoc}
     */
    public function getOriginalPrice($priceData)
    {
        $product = $this->getProduct($priceData['entity_id']);

        if ($product->getTypeId() == 'grouped') {
            return $this->groupedCatalogRuleHelper->getPrice($product);
        }

        return $product->getFinalPrice();
    }

    /**
     * Return product by id
     * @param $productId
     * @return \Magento\Catalog\Model\Product
     */
    private function getProduct($productId)
    {
        $productModel = $this->productFactory->create();
        return $productModel->load($productId);
    }
}
