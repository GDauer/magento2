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

        $this->helper->sendCustomer($order);
        $order->setNpsSent('1');
    }
}
