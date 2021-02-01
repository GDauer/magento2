<?php
namespace Itatiaia\Tracksale\Observer;

class SendTracksaleNpsAfterInvoice implements \Magento\Framework\Event\ObserverInterface
{
    private $logger;
    private $helper;

    public function __construct
    (
        \Psr\Log\LoggerInterface $logger,
        \Itatiaia\Tracksale\Helper\Data $helper
    )
    {
        $this->logger = $logger;
        $this->helper = $helper;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $invoice = $observer->getInvoice();

        $customerEmail = $invoice->getOrder()->getCustomerEmail();
        $customerName = $invoice->getOrder()->getBillingAddress()->getFirstname() . ' ' . $invoice->getOrder()->getBillingAddress()->getLastname();
        $customerPhone = $invoice->getOrder()->getBillingAddress()->getTelephone();

//        $this->logger->info('SEND NPS', [$customerEmail, $customerName, $customerPhone]);
        $this->helper->sendCustomer($customerName, $customerEmail, $customerPhone);
    }
}
