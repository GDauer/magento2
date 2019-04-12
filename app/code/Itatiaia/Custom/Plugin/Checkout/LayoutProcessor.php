<?php
namespace Itatiaia\Custom\Plugin\Checkout;

class LayoutProcessor
{
    /**
     * @param \Magento\Checkout\Block\Checkout\LayoutProcessor $subject
     * @param array $jsLayout
     * @return array
     */
    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        array $jsLayout
    ) {
        /* Shipping Address */
        $shippingAddressChildren = $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children'];

        if(isset($shippingAddressChildren['vat_id'])){
            $shippingAddressChildren['vat_id']['sortOrder'] = 50;
            $shippingAddressChildren['vat_id']['required'] = true;
            $shippingAddressChildren['vat_id']['validation'] = [
                'required-entry' => 1
            ];
        }
        if(isset($shippingAddressChildren['postcode'])){
            $shippingAddressChildren['postcode']['sortOrder'] = 60;
            $shippingAddressChildren['postcode']['additionalClasses'] = 'field-postcode';
        }

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children'] = $shippingAddressChildren;

        // print_r($jsLayout);
        // exit;

        return $jsLayout;
    }
}
