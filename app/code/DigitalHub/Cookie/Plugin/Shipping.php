<?php

namespace DigitalHub\Cookie\Plugin;

use DigitalHub\Cookie\Model\Postcode;


class Shipping
{
    /** @var \DigitalHub\Cookie\Model\Postcode  */
    protected $postcodeCookie;

    public function __construct(
        Postcode $postcodeCookie
    ) {
        $this->postcodeCookie = $postcodeCookie;
    }

    public function beforeCollect(
        \Magento\Quote\Model\Quote\Address\Total\Shipping $subject,
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    ) {
        $shipping = $shippingAssignment->getShipping();
        if (null === $shipping->getId()) {
            return [$quote, $shippingAssignment, $total];
        }

        $address = $shipping->getAddress();
        if (null === $address->getId()) {
            return [$quote, $shippingAssignment, $total];
        }

        $postcode = $address->getPostcode();
        $oldPostcode = $this->postcodeCookie->get();

        // set if the postcode if exists, and is not the same that is already saved
        if (null !== $postcode && '' !== $postcode && $oldPostcode !== $postcode) {
            $this->postcodeCookie->set($postcode);
        }

        return [$quote, $shippingAssignment, $total];
    }
}
