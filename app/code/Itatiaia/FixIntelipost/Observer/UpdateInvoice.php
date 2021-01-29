<?php
namespace Itatiaia\FixIntelipost\Observer;

use Psr\Log\LoggerInterface;
use Itatiaia\FixIntelipost\Helper\Data as intelipostHelper;
class UpdateInvoice implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var intelipostHelper
     */
    private $helper;

    public function __construct(
        LoggerInterface $logger,
        intelipostHelper $helper
    )
    {
        $this->logger = $logger;
        $this->helper = $helper;
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
        // $this->logger->info('OBSERVER UPDATE INVOICE');
        $order = $observer->getOrder();
        $this->helper->updateIntelipostInvoice($order->getId());
    }
}
