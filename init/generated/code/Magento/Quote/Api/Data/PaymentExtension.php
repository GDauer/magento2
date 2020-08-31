<?php
namespace Magento\Quote\Api\Data;

/**
 * Extension class for @see \Magento\Quote\Api\Data\PaymentInterface
 */
class PaymentExtension extends \Magento\Framework\Api\AbstractSimpleObject implements PaymentExtensionInterface
{
    /**
     * @return string[]|null
     */
    public function getAgreementIds()
    {
        return $this->_get('agreement_ids');
    }

    /**
     * @param string[] $agreementIds
     * @return $this
     */
    public function setAgreementIds($agreementIds)
    {
        $this->setData('agreement_ids', $agreementIds);
        return $this;
    }

    /**
     * @return anyType|null
     */
    public function getSwissupCheckoutFields()
    {
        return $this->_get('swissup_checkout_fields');
    }

    /**
     * @param anyType $swissupCheckoutFields
     * @return $this
     */
    public function setSwissupCheckoutFields($swissupCheckoutFields)
    {
        $this->setData('swissup_checkout_fields', $swissupCheckoutFields);
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getSwissupSubscribeAtCheckout()
    {
        return $this->_get('swissup_subscribe_at_checkout');
    }

    /**
     * @param bool $swissupSubscribeAtCheckout
     * @return $this
     */
    public function setSwissupSubscribeAtCheckout($swissupSubscribeAtCheckout)
    {
        $this->setData('swissup_subscribe_at_checkout', $swissupSubscribeAtCheckout);
        return $this;
    }
}
