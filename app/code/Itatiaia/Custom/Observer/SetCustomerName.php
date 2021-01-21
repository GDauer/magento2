<?php

namespace Itatiaia\Custom\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class SetCustomerName implements ObserverInterface
{
    /**
     * @var \Magento\Quote\Model\QuoteFactory
     */
    private $quoteFactory;

    public function __construct
    (
        \Magento\Quote\Model\QuoteFactory $quoteFactory,
        \Psr\Log\LoggerInterface $logger
    )
    {
        $this->quoteFactory = $quoteFactory;
        $this->_logger = $logger;
    }

    public function execute(Observer $observer)
    {
        $this->_logger->info('=== START SETCUSTOMERNAME OBSERVER ===');
        $order = $observer->getOrder();
        $quote = $this->quoteFactory->create()->load($order->getQuoteId());

        if(!$quote->getId()) return;

        try {
            $quoteAddress = $quote->getBillingAddress();
            $firstname = $quoteAddress->getFirstname();
            $lastname = $quoteAddress->getLastname();
            $order->setCustomerFirstname($firstname);
            $order->setCustomerLastname($lastname);
        } catch (\Exception $e) {
            $e = $e;
        }
        $this->_logger->info('=== FINISH SETCUSTOMERNAME OBSERVER ===');
    }
}