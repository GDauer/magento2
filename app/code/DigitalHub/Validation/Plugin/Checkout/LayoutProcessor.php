<?php


namespace DigitalHub\Validation\Plugin\Checkout;


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

        if(isset($shippingAddressChildren['street']['children'][0])) {
            $shippingAddressChildren['street']['children'][0]['validation'] = [
                'required-entry' => 1,
                "min_text_len‌gth" => 1,
                "max_text_length" => 60
            ];
        }

        if(isset($shippingAddressChildren['street']['children'][1])) {
            $shippingAddressChildren['street']['children'][1]['validation'] = [
                'required-entry' => 1,
                "min_text_len‌gth" => 1,
                "max_text_length" => 10
            ];
        }

        if(isset($shippingAddressChildren['street']['children'][2])) {
            $shippingAddressChildren['street']['children'][2]['validation'] = [
                "min_text_len‌gth" => 0,
                "max_text_length" => 10
            ];
        }

        if(isset($shippingAddressChildren['street']['children'][3])) {
            $shippingAddressChildren['street']['children'][3]['validation'] = [
                'required-entry' => 1,
                "validate-length" => 1,
                "max_text_length" => 40
            ];
        }

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children'] = $shippingAddressChildren;

        return $jsLayout;
    }
}
