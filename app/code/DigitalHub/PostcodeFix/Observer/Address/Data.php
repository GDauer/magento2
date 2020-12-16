<?php

namespace DigitalHub\PostcodeFix\Observer\Address;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class Data implements ObserverInterface

{

    /**
     * Add '-' to the postcode if it doesn't exist 
     *
     * @param Observer $observer
     */

    public function execute(Observer $observer)

    {
        $customerAddress = $observer->getCustomerAddress();
        $postcode = $customerAddress->getPostcode();
        if(strpos($postcode, '-') === false) {
            $splitPostcode = str_split($postcode, 5);
            $newPostcode = $splitPostcode[0].'-'.trim($splitPostcode[1]);
            $customerAddress->setPostcode($newPostcode);
        }

    }

}