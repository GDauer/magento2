<?php

namespace Itatiaia\KondutoCustom\Model\Konduto;

use Konduto\Models\Customer;

class CustomerData extends \Konduto\Antifraud\Model\Konduto\CustomerData
{
    private $order;
    public $customer;

    public function getCustomerData($order)
    {
        $this->order = $order;
        $orderAddress = $order->getShippingAddress();;

        if ($order->getCustomerIsGuest()) {
            $customerKonduto = new Customer;
            $customerKonduto->setId($order->getCustomerEmail());
            $customerKonduto->setName($order->getBillingAddress()->getFirstName());
            $customerKonduto->setEmail($orderAddress->getEmail());
            $customerKonduto->setTaxId($orderAddress->getVatId());
            $customerKonduto->setPhone1($orderAddress->getTelephone());
            return $customerKonduto;
        }
        $this->customer = $this->helper->getCustomer($order->getCustomerId());
        $customerKonduto = new Customer;
        $customerKonduto->setId($this->getKondutoIdentifier());
        $customerKonduto->setName($this->getName($this->customer->getFirstname()));
        $customerKonduto->setEmail($this->customer->getEmail());
        $customerKonduto->setDob($this->customer->getDob());
        $customerKonduto->setTaxId($this->helper->getDocumentNumber($this->customer));
        $customerKonduto->setPhone1($orderAddress->getTelephone());
        $customerKonduto->setCreatedAt($this->getCreatedAt($this->customer));
        return (object)$customerKonduto;
    }
}