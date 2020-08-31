<?php
namespace Magento\Quote\Api\Data;

/**
 * ExtensionInterface class for @see \Magento\Quote\Api\Data\PaymentInterface
 */
interface PaymentExtensionInterface extends \Magento\Framework\Api\ExtensionAttributesInterface
{
    /**
     * @return string[]|null
     */
    public function getAgreementIds();

    /**
     * @param string[] $agreementIds
     * @return $this
     */
    public function setAgreementIds($agreementIds);

    /**
     * @return anyType|null
     */
    public function getSwissupCheckoutFields();

    /**
     * @param anyType $swissupCheckoutFields
     * @return $this
     */
    public function setSwissupCheckoutFields($swissupCheckoutFields);

    /**
     * @return bool|null
     */
    public function getSwissupSubscribeAtCheckout();

    /**
     * @param bool $swissupSubscribeAtCheckout
     * @return $this
     */
    public function setSwissupSubscribeAtCheckout($swissupSubscribeAtCheckout);
}
