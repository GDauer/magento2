<?php
namespace Itatiaia\CheckoutBillingAddress\Observer;

class DefaultBillingAddress implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $logger = $objectManager->get('\Psr\Log\LoggerInterface');

        $order = $observer->getOrder();
        // $logger->info('ORDER PLACE AFTER', [$order->getData()]);

        $customerId = $order->getCustomerId();
        $customer = $objectManager->get('\Magento\Customer\Model\CustomerFactory')->create();
        $customer->load($customerId);

        if($customer->getId()){
            // $logger->info('ORDER PLACE AFTER :: CUSTOMER', [$customer->getData()]);
            // $logger->info('ORDER PLACE AFTER :: DEFAULT BILLING ADDRESS', [$customer->getDefaultBillingAddress()]);

            if(!$customer->getDefaultBillingAddress()){
                $newDefaultBillingAddress = null;
                $addresses = $customer->getAddresses();
                foreach($addresses as $address){
                    $newDefaultBillingAddress = $address;
                }

                // $logger->info('ORDER PLACE AFTER :: NEW DEFAULT BILLING ADDRESS', [$newDefaultBillingAddress->getData()]);
                $newDefaultBillingAddress->setIsDefaultBilling(1);
                $newDefaultBillingAddress->save();
            }
        }
    }
}
