<?php

namespace Itatiaia\Custom\Observer\Frontend;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Psr\Log\LoggerInterface;

/**
 * Google Analytics module observer
 *
 */
class SetLastOrderIdOnSessionToGaAfterSaveObserver implements ObserverInterface
{
    /**
     * @var CheckoutSession
     */
    protected $checkoutSession;

    /**
     * @var LoggerInterface
     */
    protected $_logger;

    /**
     * @param CheckoutSession $checkoutSession
     */
    public function __construct(
        CheckoutSession $checkoutSession,
        LoggerInterface $_logger
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->_logger = $_logger;
    }

    /**
     * Add order information into GA block to render on checkout success pages
     *
     * @param EventObserver $observer
     * @return void
     */
    public function execute(EventObserver $observer)
    {
        $this->_logger->info('=== START SETLASTORDER OBSERVER ===');
        $order = $observer->getEvent()->getOrder();
        $orderId = $order->getId() ? $order->getId() : $order->getEntityId();
        $this->_logger->info('ORDER: getEntityId' .$order->getId());
        $this->_logger->info('ORDER: getId' .$order->getEntityId());
        $this->_logger->info('ORDER FINAL: ' .$orderId);
        if ($order->hasError()) {
            $this->_logger->info('ORDER HAS ERROR');
            return;
        }

        $this->checkoutSession->setCustomLastOrderId($orderId);
        $this->_logger->info('=== FINISH SETLASTORDER OBSERVER ===');
    }
}
