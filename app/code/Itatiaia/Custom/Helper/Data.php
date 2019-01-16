<?php
namespace Itatiaia\Custom\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    public function verifyIsGroupedLockedItems($quoteItem)
    {
        $logger = \Magento\Framework\App\ObjectManager::getInstance()->get('\Psr\Log\LoggerInterface');

        if($parentProduct = $this->getGroupedByQuoteItem($quoteItem)){
            if($parentProduct->getLockItems()){
                return true;
            }
        }

        return false;
    }

    function getGroupedByQuoteItem($quoteItem)
    {
        $isGroupedItem = false;
        $groupedProductId = false;
        foreach($quoteItem->getOptions() as $option){
            if($option['code'] == 'product_type' && $option['value'] == 'grouped'){
                $groupedProductId = $option['product_id'];
                $isGroupedItem = true;
                break;
            }
        }

        if($isGroupedItem){
            $productFactory = \Magento\Framework\App\ObjectManager::getInstance()->get('\Magento\Catalog\Model\ProductFactory');
            $parentProduct = $productFactory->create()->load($groupedProductId);
            return $parentProduct;
        }

        return false;
    }
}
