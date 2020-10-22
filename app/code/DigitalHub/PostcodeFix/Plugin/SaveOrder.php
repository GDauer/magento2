<?php


namespace DigitalHub\PostcodeFix\Plugin;

use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\Data\OrderInterface;


class SaveOrder
{
    public function beforeSave(OrderRepositoryInterface $orderRepo, OrderInterface $order)
    {
        $billingAddress = $order->getBillingAddress();
        $shippingAddress = $order->getShippingAddress();
        if (!empty($billingAddress->getData())) {
            $postcode = $billingAddress->getPostcode();
            $postcode = $this->correctPostcode($postcode);
            $billingAddress->setPostcode($postcode);
            $order->setBillingAddress($billingAddress);
        }
        if (!empty($shippingAddress->getData())) {
            $postcode = $shippingAddress->getPostcode();
            $postcode = $this->correctPostcode($postcode);
            $shippingAddress->setPostcode($postcode);
            $order->setShippinAddress($shippingAddress);
        }
        return [$order];
    }

    private function correctPostcode($postcode) {
        if(strpos($postcode, '-') === false) {
            $splitPostcode = str_split($postcode, 5);
            $postcode = $splitPostcode[0] . '-' . trim($splitPostcode[1]);
        }
        return $postcode;
    }
}