<?php
namespace Itatiaia\Custom\Observer;

class RemoveGroupLockedItems implements \Magento\Framework\Event\ObserverInterface
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
        $quoteItem = $observer->getItem();

        $logger = \Magento\Framework\App\ObjectManager::getInstance()->get('\Psr\Log\LoggerInterface');
        $logger->info('REMOVE QUOTE ITEM', $quoteItem);

        throw new Exception('TESTE EXCEPTION');
    }
}
