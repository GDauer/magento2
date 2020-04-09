<?php

namespace Itatiaia\CreateCustomer\Observer;

use Magento\Customer\Model\AddressFactory;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\SessionFactory;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

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
     * @var SessionFactory
     */
    private $sessionFactory;

    /**
     * @var logger
     */
    private $logger;

    public function __construct
    (
        SessionFactory $sessionFactory,
        StoreManagerInterface $storeManager,
        CustomerFactory $customerFactory,
        AddressFactory $addressFactory,
        LoggerInterface $logger
    )
    {
        $this->customerFactory = $customerFactory;
        $this->addressFactory = $addressFactory;
        $this->storeManager = $storeManager;
        $this->sessionFactory = $sessionFactory;
    }

    public function execute(Observer $observer)
    {
        $order = $observer->getOrder();
        $orderAddress = $order->getShippingAddress();
        $session = $this->sessionFactory->create();

        $customerEmail = $order->getCustomerEmail();

        if(!$order->getCustomerIsGuest()){
            return;
        }

        try {
            $customer = $this->customerFactory->create();
            $customerCollection = $customer->getCollection()->addFieldToFilter('email', $customerEmail);

            if (!$customerCollection->getSize()) {
                $customer = $this->customerFactory->create();
                $customer->setFirstname($orderAddress->getFirstname());
                $customer->setEmail($orderAddress->getEmail());
                $customer->setLastname($orderAddress->getLastname());
                $customer->setTaxvat($orderAddress->getVatId());
                $customer->setStore($this->storeManager->getStore());
                $customer->setPassword(uniqid());

                $customer->save();
            } else {
                $customer = $customerCollection->getFirstItem();
            }

            $address = $this->addressFactory->create();
            $address->setCustomerId($customer->getId())
                ->setFirstname($orderAddress->getFirstname())
                ->setLastname($orderAddress->getLastname())
                ->setCountryId($orderAddress->getCountryId())
                ->setRegionId($orderAddress->getRegionId())
                ->setRegion($orderAddress->getRegion())
                ->setPostcode($orderAddress->getPostcode())
                ->setCity($orderAddress->getCity())
                ->setTelephone($orderAddress->getTelephone())
                ->setStreet($orderAddress->getStreet())
                ->setIsDefaultBilling('1')
                ->setIsDefaultShipping('1')
                ->setSaveInAddressBook('1');

            $address->save();
            $order->setCustomerId($customer->getId());
            $order->setCustomerIsGuest(0);
            $order->setCustomerFirstname($orderAddress->getFirstname());
            $order->setCustomerLastname($orderAddress->getLastname());
            $order->setCustomerGroupId($customer->getGroupId());

            if ($order->getShippingAddress()) {
                $order->setCustomerTaxvat($order->getShippingAddress()->getVatId());
            }

            $order->save();
            $session->setCustomerAsLoggedIn($customer);

            $customer->sendPasswordResetConfirmationEmail();
        } catch (Exception $e) {
            $this->logger->critical('Erro no modulo CreateCustomer', ['exception' => $e]);
        }
    }
}