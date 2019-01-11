<?php
namespace Itatiaia\GroupedCatalogRule\Observer;

class GroupedCustomPrice implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        $logger = \Magento\Framework\App\ObjectManager::getInstance()->get('\Psr\Log\LoggerInterface');

        $quoteItem = $observer->getQuoteItem();
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
            $logger->info('GROUPED ID', [$groupedProductId]);
            $productFactory = \Magento\Framework\App\ObjectManager::getInstance()->get('\Magento\Catalog\Model\ProductFactory');
            $groupedCatalogRuleHelper = \Magento\Framework\App\ObjectManager::getInstance()->get('\Itatiaia\GroupedCatalogRule\Helper\Data');

            $parentProduct = $productFactory->create()->load($groupedProductId);
            $childProduct = $productFactory->create()->load($quoteItem->getProduct()->getId());

            $logger->info('GROUPED ID', [
                'grouped' => $parentProduct->getId(),
                'item' => $childProduct->getId()
            ]);

            $newPrice = $groupedCatalogRuleHelper->getFinalPrice($childProduct, $parentProduct);
            $quoteItem->setCustomPrice($newPrice);
            $quoteItem->setOriginalCustomPrice($newPrice);
        }

    }
}
