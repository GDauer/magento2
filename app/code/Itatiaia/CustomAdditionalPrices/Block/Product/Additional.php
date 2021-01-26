<?php
namespace Itatiaia\CustomAdditionalPrices\Block\Product;

class Additional extends \DigitalHub\AdditionalPrices\Block\Product\Additional
{
    public function calculateFinalPrice($_product, $customPrice = false)
    {
        if($_product && !$customPrice){
            if($_product->getTypeId() == 'grouped'){
                $groupedCatalogRuleHelper = \Magento\Framework\App\ObjectManager::getInstance()->get('\Itatiaia\GroupedCatalogRule\Helper\Data');
                return $groupedCatalogRuleHelper->getFinalPrice($_product);
            }
            return $_product->getFinalPrice();
        } else {
            return $customPrice;
        }
    }
}
