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
        $quoteItem = $observer->getQuoteItem();

        $logger = \Magento\Framework\App\ObjectManager::getInstance()->get('\Psr\Log\LoggerInterface');
        $logger->info('REMOVE QUOTE ITEM', [$quoteItem]);

        $helper = \Magento\Framework\App\ObjectManager::getInstance()->get('\Itatiaia\Custom\Helper\Data');

        if($helper->verifyIsGroupedLockedItems($quoteItem)){
            $logger->info('REMOVER ITENS DO MESMO AGRUPADO');
            // Add info no registro para evitar loop de chamadas ao remover outros quoteItems !!!
            $this->removeAllItemsOfGrouped($quoteItem);
        } else {
            $logger->info('NÃO REMOVER');
        }

        throw new \Magento\Framework\Exception\LocalizedException(__('TESTE EXCEPTION'));
    }

    private function removeAllItemsOfGrouped($quoteItem)
    {
        $helper = \Magento\Framework\App\ObjectManager::getInstance()->get('\Itatiaia\Custom\Helper\Data');
        $parentProduct = $helper->getGroupedByQuoteItem($quoteItem);

        // $quoteItems =
    }
}
