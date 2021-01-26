<?php
namespace Itatiaia\GroupedCatalogRule\Observer;

class GroupedStockStatus implements \Magento\Framework\Event\ObserverInterface
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
        $helper = \Magento\Framework\App\ObjectManager::getInstance()->get('\Itatiaia\GroupedCatalogRule\Helper\Data');
        $helper->updateGroupedStockStatusByStockItem($observer->getItem());
    }
}
