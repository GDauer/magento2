<?php
namespace Itatiaia\Custom\Observer;

class FixVatId implements \Magento\Framework\Event\ObserverInterface
{

    private $_checkoutSession;
    private $_logger;

    public function __construct
    (
        \Magento\Checkout\Model\Session $checkouSession,
        \Psr\Log\LoggerInterface $logger
    )
    {
        $this->_checkoutSession = $checkouSession;
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
        $order = $observer->getOrder();
        $billingAddress = $order->getBillingAddress();

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $helper = $objectManager->get('\DigitalHub\CustomerAttributes\Helper\Data');
        
        if(!$order->getCustomerTaxvat()){
            $order->setCustomerTaxvat($billingAddress->getVatId());
        }
    }
}
