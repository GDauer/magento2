<?php
namespace Itatiaia\GroupedCatalogRule\Plugin;

class ProductPlugin
{
    public function afterIsAvailable(\Magento\Catalog\Model\Product $product, $result)
    {
        $isAvailable = $result;

        if($isAvailable && $product->getTypeId() == 'grouped'){
            $helper = \Magento\Framework\App\ObjectManager::getInstance()->get('\Itatiaia\GroupedCatalogRule\Helper\Data');
            if(!$helper->isGroupedProductInStock($product)){
                $isAvailable = false;
            }
        }

        return $isAvailable;
    }
}
