<?php
namespace Magento\Checkout\Api\Data;

/**
 * Extension class for @see \Magento\Checkout\Api\Data\ShippingInformationInterface
 */
class ShippingInformationExtension extends \Magento\Framework\Api\AbstractSimpleObject implements ShippingInformationExtensionInterface
{
    /**
     * @return string|null
     */
    public function getDeliveryDate()
    {
        return $this->_get('delivery_date');
    }

    /**
     * @param string $deliveryDate
     * @return $this
     */
    public function setDeliveryDate($deliveryDate)
    {
        $this->setData('delivery_date', $deliveryDate);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDeliveryTime()
    {
        return $this->_get('delivery_time');
    }

    /**
     * @param string $deliveryTime
     * @return $this
     */
    public function setDeliveryTime($deliveryTime)
    {
        $this->setData('delivery_time', $deliveryTime);
        return $this;
    }
}
