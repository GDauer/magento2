<?php
namespace Itatiaia\AdyenCustom\Gateway\Request;

use Magento\Payment\Gateway\Request\BuilderInterface;

/**
 * Class AddressDataBuilder
 */
class AddressDataBuilder extends \Adyen\Payment\Gateway\Request\AddressDataBuilder
{

    /**
     * Add delivery\billing details into request
     *
     * @param array $buildSubject
     * @return array
     */
    public function build(array $buildSubject)
    {
        /** @var \Magento\Payment\Gateway\Data\PaymentDataObject $paymentDataObject */
        $paymentDataObject = \Magento\Payment\Gateway\Helper\SubjectReader::readPayment($buildSubject);
        $order = $paymentDataObject->getOrder();

        $result = [];

        $billingAddress = $order->getBillingAddress();
        if ($billingAddress) {
            $requestBilling = ["street" => "N/A",
                "postalCode" => '',
                "city" => "N/A",
                "houseNumberOrName" => $billingAddress->getStreetLine2(),
                "stateOrProvince" => '',
                "country" => "ZZ"
            ];

            if ($billingAddress->getStreetLine1()) {
                $requestBilling["street"] = $billingAddress->getStreetLine1();
            }

            if ($billingAddress->getPostcode()) {
                $requestBilling["postalCode"] = preg_replace('/\D+/', '', $billingAddress->getPostcode());
            }

            if ($billingAddress->getCity()) {
                $requestBilling["city"] = $billingAddress->getCity();
            }

            if ($billingAddress->getRegionCode()) {
                $requestBilling["stateOrProvince"] = $billingAddress->getRegionCode();
            }

            if ($billingAddress->getCountryId()) {
                $requestBilling["country"] = $billingAddress->getCountryId();
            }

            $result['billingAddress'] = $requestBilling;
        }

        $shippingAddress = $order->getShippingAddress();
        if ($shippingAddress) {
            // filter housenumber from streetLine1
            $requestDelivery = ["street" => $shippingAddress->getStreetLine1(),
                "postalCode" => preg_replace('/\D+/', '', $shippingAddress->getPostcode()),
                "city" => $shippingAddress->getCity(),
                "houseNumberOrName" => $shippingAddress->getStreetLine2(),
                "stateOrProvince" => $shippingAddress->getRegionCode(),
                "country" => $shippingAddress->getCountryId()
            ];


            $result['deliveryAddress'] = $requestDelivery;
        }

        return $result;
    }
}
