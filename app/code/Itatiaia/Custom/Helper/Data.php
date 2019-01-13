<?php
namespace Itatiaia\Custom\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    public function verifyIsGroupedLockedItems($quoteItem)
    {
        $logger = \Magento\Framework\App\ObjectManager::getInstance()->get('\Psr\Log\LoggerInterface');
        // $logger->info('VERIFICADOR GROUPED', [$quoteItem]);

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
            // $logger->info('VERIFICADOR GROUPED :: IS_LOCKED');
            $productFactory = \Magento\Framework\App\ObjectManager::getInstance()->get('\Magento\Catalog\Model\ProductFactory');
            $parentProduct = $productFactory->create()->load($groupedProductId);

            if($parentProduct->getLockItems()){
                // $logger->info('VERIFICADOR GROUPED :: LOCKED_ITEMS');
                return true;
            }
        }

        return false;
    }
}
