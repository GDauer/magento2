<?php

namespace Itatiaia\Custom\Observer\Frontend;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\LayoutInterface;

/**
 * Google Analytics module observer
 *
 */
class SetGaOrderdataAfterPaymentObserver implements ObserverInterface
{
    /**
     * @var LayoutInterface
     */
    protected $layout;

    /**
     * @param LayoutInterface $layout
     */
    public function __construct(
        LayoutInterface $layout
    ) {
        $this->layout = $layout;
    }

    /**
     * Add order information into GA block to render on checkout success pages
     *
     * @param EventObserver $observer
     * @return void
     */
    public function execute(EventObserver $observer)
    {
        $order = $observer->getEvent()->getOrder();
        if ($order->hasError()) {
            return;
        }

        $block = $this->layout->createBlock(
            '\Itatiaia\Custom\Block\GoogleTagManager\CustomDataLayer',
            'custom_magepal_gtm_datalayer',
            ['data' => ['show_ecommerce_currency_code' => true]]
        );
        
        if ($block) {
            $orderIds = [$order->getId()];
            $block->setOrderIds($orderIds);
        }
    }
}
