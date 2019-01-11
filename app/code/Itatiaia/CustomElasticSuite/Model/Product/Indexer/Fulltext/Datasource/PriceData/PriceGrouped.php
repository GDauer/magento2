<?php
namespace Itatiaia\CustomElasticSuite\Model\Product\Indexer\Fulltext\Datasource\PriceData;

// Customização para colocar preço default para produtos agrupados na indexação (ajuste no filtro com valor min-max)

class PriceGrouped extends \Smile\ElasticsuiteCatalog\Model\Product\Indexer\Fulltext\Datasource\PriceData\PriceGrouped
{
    /**
     * {@inheritDoc}
     */
    public function getPrice($priceData)
    {
        return $this->getOriginalPrice($priceData);
    }

    /**
     * {@inheritDoc}
     */
    public function getOriginalPrice($priceData)
    {
        $groupedCatalogRuleHelper = \Magento\Framework\App\ObjectManager::getInstance()->get('Itatiaia\GroupedCatalogRule\Helper\Data');
        $productModel = \Magento\Framework\App\ObjectManager::getInstance()->get('\Magento\Catalog\Model\ProductFactory')->create();
        $product = $productModel->load($priceData['entity_id']);

        $defaultPrice = $product->getFinalPrice();
        if($product->getTypeId() == 'grouped'){
            $defaultPrice = $groupedCatalogRuleHelper->getFinalPrice($product);
        }

        // $defaultPrice = 0;
        // $associatedProducts = $product->getTypeInstance(true)->getAssociatedProducts($product);
        // foreach ($associatedProducts as $item) {
        //     $qty = $item->getQty() ? $item->getQty() : 1;
        //     $childProduct = \Magento\Framework\App\ObjectManager::getInstance()->get('\Magento\Catalog\Model\Product')->load($item->getId());
        //     $defaultPrice = $defaultPrice + ((float)$childProduct->getFinalPrice() * (int)$qty);
        // }

        return $defaultPrice;
    }
}
