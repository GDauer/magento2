<?php
namespace Itatiaia\Custom\Observer;

class RemoveGroupLockedItems implements \Magento\Framework\Event\ObserverInterface
{

    private $_customHelper;
    private $_checkoutSession;
    private $_logger;

    public function __construct
    (
        \Itatiaia\Custom\Helper\Data $customHelper,
        \Magento\Checkout\Model\Session $checkouSession,
        \Psr\Log\LoggerInterface $logger
    )
    {
        $this->_customHelper = $customHelper;
        $this->_checkoutSession = $checkouSession;
        $this->_logger = $logger;
    }
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
        // $this->_logger->info('REMOVE QUOTE ITEM', [$quoteItem]);

        if($this->_customHelper->verifyIsGroupedLockedItems($quoteItem)){
            // $this->_logger->info('REMOVER ITENS DO MESMO AGRUPADO');
            $this->removeAllItemsOfGrouped($quoteItem);
        }
    }

    private function removeAllItemsOfGrouped($quoteItem)
    {
        // $this->_logger->info('PRE REMOVE');
        $parentProduct = $this->_customHelper->getGroupedByQuoteItem($quoteItem);

        $quoteItems = $this->_checkoutSession->getQuote()->getAllItems();
        // $this->_logger->info('COUNT', [count($quoteItems)]);

        foreach($quoteItems as $item){
            if($parentQuoteItem = $this->_customHelper->getGroupedByQuoteItem($item)){
                if($parentQuoteItem->getId() == $parentProduct->getId()){
                    // $this->_logger->info('REMOVER', [$item->getName()]);
                    $item->delete();
                }
            }
        }
    }
}
