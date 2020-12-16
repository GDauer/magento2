<?php
namespace DigitalHub\CustomerAttributes\Plugin\Checkout;

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

        // Endereços no padrão 4 linhas
        // 0 = Endereço
        // 1 = Número
        // 2 = Complemento
        // 3 = Bairro

        $shippingAddressChildren['street']['config']['template'] = 'DigitalHub_CustomerAttributes/ui/group/group-street';

        if(isset($shippingAddressChildren['street']['children'][0])){
            $shippingAddressChildren['street']['children'][0]['label'] = __('Endereço');
            $shippingAddressChildren['street']['children'][0]['additionalClasses'] = 'field';
            $shippingAddressChildren['street']['children'][0]['validation'] = [
                'required-entry' => 1,
                'max_text_length' => 255,
                'min_text_length' => 1
            ];
        }

        if(isset($shippingAddressChildren['street']['children'][1])){
            $shippingAddressChildren['street']['children'][1]['label'] = __('Número');
            $shippingAddressChildren['street']['children'][1]['additionalClasses'] = 'field';
            $shippingAddressChildren['street']['children'][1]['validation'] = [
                'required-entry' => 1,
                'max_text_length' => 255,
                'min_text_length' => 1
            ];
        }

        if(isset($shippingAddressChildren['street']['children'][2])){
            $shippingAddressChildren['street']['children'][2]['label'] = __('Complemento');
            $shippingAddressChildren['street']['children'][2]['additionalClasses'] = 'field';
        }

        if(isset($shippingAddressChildren['street']['children'][3])){
            $shippingAddressChildren['street']['children'][3]['label'] = __('Bairro');
            $shippingAddressChildren['street']['children'][3]['additionalClasses'] = 'field';
            $shippingAddressChildren['street']['children'][3]['validation'] = [
                'required-entry' => 1,
                'max_text_length' => 255,
                'min_text_length' => 1
            ];
        }

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children'] = $shippingAddressChildren;

        /* Billing Address */
        $billingAddressChildren = $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']
            ['children']['payment']['children']['afterMethods']['children']['billing-address-form']['children']['form-fields']['children'];

        $billingAddressChildren['street']['config']['template'] = 'DigitalHub_CustomerAttributes/ui/group/group-street';

        if(isset($billingAddressChildren['street']['children'][0])){
            $billingAddressChildren['street']['children'][0]['label'] = __('Endereço');
            $billingAddressChildren['street']['children'][0]['additionalClasses'] = 'field';
            $billingAddressChildren['street']['children'][0]['validation'] = [
                'required-entry' => 1,
                'max_text_length' => 255,
                'min_text_length' => 1
            ];
        }

        if(isset($billingAddressChildren['street']['children'][1])){
            $billingAddressChildren['street']['children'][1]['label'] = __('Número');
            $billingAddressChildren['street']['children'][1]['additionalClasses'] = 'field';
            $billingAddressChildren['street']['children'][1]['validation'] = [
                'required-entry' => 1,
                'max_text_length' => 255,
                'min_text_length' => 1
            ];
        }

        if(isset($billingAddressChildren['street']['children'][2])){
            $billingAddressChildren['street']['children'][2]['label'] = __('Complemento');
            $billingAddressChildren['street']['children'][2]['additionalClasses'] = 'field';
        }

        if(isset($billingAddressChildren['street']['children'][3])){
            $billingAddressChildren['street']['children'][3]['label'] = __('Bairro');
            $billingAddressChildren['street']['children'][3]['additionalClasses'] = 'field';
            $billingAddressChildren['street']['children'][3]['validation'] = [
                'required-entry' => 1,
                'max_text_length' => 255,
                'min_text_length' => 1
            ];
        }


        $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']
                ['children']['payment']['children']['afterMethods']['children']['billing-address-form']['children']['form-fields']['children'] = $billingAddressChildren;

        return $jsLayout;
    }
}
