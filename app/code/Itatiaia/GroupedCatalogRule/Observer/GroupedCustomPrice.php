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
        $logger->info('GROUPED SET CUSTOM PRICE');
        $logger->info('PARAMS', [$observer->getProduct()->getTypeId()]);

        // $quoteItem = $observer->getQuoteItem();
        // if($quoteItem->getParentItemId()){
        //     // $product = $quoteItem->getProduct();
        //     // if($quoteItem->getParentItem()->getProduct())
        //     $logger->info('QUOTE ITEM PARENT DATA', $quoteItem->getParentItem()->getProduct()->getData());
        // }

        // $logger->info('QUOTE ITEM DATA', [$quoteItem->getProductType()]);
        // $logger->info('QUOTE ITEM DATA', [$quoteItem->getProduct()->getData()]);
        // $logger->info('QUOTE ITEM DATA', [get_class_methods($quoteItem)]);

        // $quote = $quoteItem->getQuote();
        // $logger->info('QUOTE DATA', [$quoteItem->getProductType()]);

        // $quoteItem->setCustomPrice(100);
        // $quoteItem->setOriginalCustomPrice(100);
    }
}
