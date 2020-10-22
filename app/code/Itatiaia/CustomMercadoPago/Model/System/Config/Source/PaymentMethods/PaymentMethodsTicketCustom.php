<?php

namespace Itatiaia\CustomMercadoPago\Model\System\Config\Source\PaymentMethods;

use MercadoPago\Core\Model\System\Config\Source\PaymentMethods\PaymentMethodsAbstract;

/**
 * Class PaymentMethodsTicket
 * @package MercadoPago\Core\Model\System\Config\Source\PaymentMethods
 */
class PaymentMethodsTicketCustom extends PaymentMethodsAbstract implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $response = parent::toOptionArray();
        $methods[] = reset($response['methods']);

        if (isset($response['success'])) {
            foreach ($response['success'] as $pm) {
                if (isset($pm['payment_type_id']) && $pm['payment_type_id'] == "ticket" || $pm['payment_type_id'] == "atm") {
                    $methods[] = ['value' => $pm['id'], 'label' => __($pm['name'])];
                }
            }
        }

        foreach($methods as $key=>$value)
        {
            if(is_null($key) || $key == '')
                unset($methods[$key]);
        }

        $this->coreHelper->log("PaymentMethodsTicket:: Displayed", 'mercadopago', $methods);

        return $methods;
    }
}
