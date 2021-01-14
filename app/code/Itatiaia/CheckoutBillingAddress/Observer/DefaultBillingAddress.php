<?php
namespace Itatiaia\CheckoutBillingAddress\Observer;

use \Psr\Log\LoggerInterface;
use \Magento\Customer\Model\CustomerFactory;

class DefaultBillingAddress implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var CustomerFactory
     */
    private $customerFactory;

    function __construct(
        LoggerInterface $logger,
        CustomerFactory $customerFactory
    )
    {
        $this->logger = $logger;
        $this->customerFactory = $customerFactory;
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
        // $logger->info('ORDER PLACE AFTER', [$order->getData()]);

        $customerId = $order->getCustomerId();
        $customer = $this->customerFactory->create();
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
