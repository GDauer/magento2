<?php
namespace Itatiaia\Custom\Observer;

class AssociateGuestOrders implements \Magento\Framework\Event\ObserverInterface
{
    private $orderFactory;
    private $_logger;

    public function __construct
    (
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Psr\Log\LoggerInterface $logger
    )
    {
        $this->orderFactory = $orderFactory;
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
        $customer = $observer->getCustomer();

        try {
            $orderCollection = $this->orderFactory->create()->getCollection();
            $orderCollection->addFieldToFilter('customer_is_guest', 1);
            $orderCollection->addFieldToFilter('customer_email', $customer->getEmail());
            $orderCollection->load();

            $this->_logger->info('GUEST ORDERS TO ASSIGN', [$orderCollection->getSize()]);

            foreach($orderCollection as $order){
                $order->setCustomerId($customer->getId());
                $order->setCustomerIsGuest(0);
                $order->save();
            }
        } catch (\Exception $e){
            $this->_logger->error('ASSIGN ORDER ERROR', [$order->getId(), $e->getMessage()]);
        }
    }
}
