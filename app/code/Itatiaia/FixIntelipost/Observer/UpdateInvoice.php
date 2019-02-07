<?php
namespace Itatiaia\FixIntelipost\Observer;

class UpdateInvoice implements \Magento\Framework\Event\ObserverInterface
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
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $logger = $objectManager->get('\Psr\Log\LoggerInterface');
        $logger->info('OBSERVER UPDATE INVOICE');

        $order = $observer->getOrder();

        $helper = $objectManager->get('Itatiaia\FixIntelipost\Helper\Data');
        $helper->updateIntelipostInvoice($order->getId());
    }
}
