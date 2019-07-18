<?php

namespace Itatiaia\CreateCustomer\Observer;

use Magento\Customer\Model\AddressFactory;
use Magento\Customer\Model\CustomerFactory;
use Magento\Directory\Model\RegionFactory;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\StoreManagerInterface;

class CreateCustomer implements ObserverInterface
{
    /**
     * @var CustomerFactory
     */
    private $customerFactory;
    /**
     * @var AddressFactory
     */
    private $addressFactory;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var RegionFactory
     */
    private $regionFactory;

    public function __construct
    (
        RegionFactory $regionFactory,
        StoreManagerInterface $storeManager,
        CustomerFactory $customerFactory,
        AddressFactory $addressFactory
    )
    {
        $this->customerFactory = $customerFactory;
        $this->addressFactory = $addressFactory;
        $this->storeManager = $storeManager;
        $this->regionFactory = $regionFactory;
    }

    public function execute(Observer $observer)
    {
        $order = $observer->getOrder();
        $orderAddress = $order->getBillingAddress();

        $customerEmail = $order->getCustomerEmail();

        if(!$order->getCustomerIsGuest()){
            return;
        }
        $customer = $this->customerFactory->create();

        if(!count($customer->getCollection()->addFieldToFilter('email', $customerEmail)))
        {
            $customer = $this->customerFactory->create();
            $customer->setFirstname($order->getCustomerFirstname());
            $customer->setEmail($order->getCustomerEmail());
            $customer->setLastname($order->getCustomerLastname());
            $customer->setTaxvat($order->getCustomerTaxvat());
            $customer->setStore($this->storeManager->getStore());
            $customer->setPassword('teste123');

            $customer->save();

            $regionName = $this->regionFactory->create()->load($orderAddress->getRegionId())->getName();
            $address = $this->addressFactory->create();
            $address->setCustomerId($customer->getId())
                ->setFirstname($order->getCustomerFirstname())
                ->setLastname($order->getCustomerLastname())
                ->setCountryId('BR')
                ->setRegionId($orderAddress->getRegionId())
                ->setRegion($regionName)
                ->setPostcode($orderAddress->getPostcode())
                ->setCity($orderAddress->getCity())
                ->setTelephone($orderAddress->getTelephone)
                ->setStreet(array(
                    '0' => $orderAddress->getStreet(1),
                    '1' => $orderAddress->getStreet(2),
                    '2' => $orderAddress->getStreet(3),
                    '3' => $orderAddress->getStreet(4)
                ))
                ->setIsDefaultBilling('1')
                ->setIsDefaultShipping('1')
                ->setSaveInAddressBook('1');

            try {
                $address->save();
            } catch (Exception $e) {

            }
            $customer->sendNewAccountEmail();
        }
    }
}