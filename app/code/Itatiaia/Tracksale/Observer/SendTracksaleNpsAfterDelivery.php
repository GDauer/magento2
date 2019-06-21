<?php
namespace Itatiaia\Tracksale\Observer;

class SendTracksaleNpsAfterDelivery implements \Magento\Framework\Event\ObserverInterface
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
        $order = $observer->getOrder();

        $customerEmail = $order->getCustomerEmail();
        $customerName = $order->getBillingAddress()->getFirstname() . ' ' . $order->getBillingAddress()->getLastname();
        $customerPhone = $order->getBillingAddress()->getTelephone();

        $this->logger->info('SEND NPS', [$customerEmail, $customerName, $customerPhone]);
        $this->helper->sendCustomer($customerName, $customerEmail, $customerPhone);
        $order->setNpsSent('1');
    }
}
