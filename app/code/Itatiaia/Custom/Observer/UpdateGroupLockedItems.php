<?php
namespace Itatiaia\Custom\Observer;

class UpdateGroupLockedItems implements \Magento\Framework\Event\ObserverInterface
{

    private $_customHelper;
    private $_checkoutSession;
    private $_registry;
    private $_messageManager;
    private $_logger;

    public function __construct
    (
        \Itatiaia\Custom\Helper\Data $customHelper,
        \Magento\Checkout\Model\Session $checkouSession,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Message\ManagerInterface $messageManager
    )
    {
        $this->_customHelper = $customHelper;
        $this->_checkoutSession = $checkouSession;
        $this->_registry = $registry;
        $this->_messageManager = $messageManager;
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
        $quoteItem = $observer->getItem();
        if(!$this->_registry->registry('is_updating') && !$quoteItem->isObjectNew()){
            // $this->_logger->info('UPDATE ITEM', $quoteItem->getData());
            if($this->_customHelper->verifyIsGroupedLockedItems($quoteItem)){
                // $this->_logger->info('UPDATE ITEMS QTY');
                $this->_registry->register('is_updating', true);
                $this->updateAllLockedItemsQty($quoteItem);
            }
        }
    }

    private function updateAllLockedItemsQty($quoteItem)
    {
        // $this->_logger->info('PRE UPDATE');
        $parentProduct = $this->_customHelper->getGroupedByQuoteItem($quoteItem);

        $quoteItems = $this->_checkoutSession->getQuote()->getAllItems();
        // $this->_logger->info('COUNT', [count($quoteItems)]);

        foreach($quoteItems as $item){
            if($parentQuoteItem = $this->_customHelper->getGroupedByQuoteItem($item)){
                if($parentQuoteItem->getId() == $parentProduct->getId()){
                    $item->setQty($quoteItem->getQty());
                    $item->save();
                    $this->_logger->info('SAVE QTY', []);
                }
            }
        }

        $this->_messageManager->addNotice(__('Este módulo faz parte de um conjunto onde seus módulos não podem ser vendidos separadamente. Por isso, mudamos a quantidade dos outros módulos do mesmo conjunto para a quantidade indicada.'));
    }
}
