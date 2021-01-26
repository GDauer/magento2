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
        \Magento\Quote\Model\QuoteFactory $quoteFactory
    )
    {
        $this->quoteFactory = $quoteFactory;
    }

    public function execute(Observer $observer)
    {
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
    }
}