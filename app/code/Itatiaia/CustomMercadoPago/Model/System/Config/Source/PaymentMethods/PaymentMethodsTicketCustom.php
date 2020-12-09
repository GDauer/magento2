<?php

/**
 * PHP version 7
 *
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2020 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 * @link        http://www.webjump.com.br
 */

declare(strict_types=1);

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
    public function toOptionArray(): array
    {
        $response = parent::toOptionArray();
        $methods[] = reset($response['methods']);

        if (isset($response['success'])) {
            foreach ($response['success'] as $pm) {
                if (isset($pm['payment_type_id'])
                    && ($pm['payment_type_id'] == \Itatiaia\CustomMercadoPago\Model\CustomTicket\Payment::PAYMENT_TYPE_TICKET
                        || $pm['payment_type_id'] == \Itatiaia\CustomMercadoPago\Model\CustomTicket\Payment::PAYMENT_TYPE_ATM))
                {
                    $methods[] = ['value' => $pm['id'], 'label' => __($pm['name'])];
                }
            }
        }

        $this->removeEmptyKey($methods);

        $this->coreHelper->log("PaymentMethodsTicket:: Displayed", 'mercadopago', $methods);

        return $methods;
    }

    /**
     * @param array &$methods
     */
    private function removeEmptyKey(&$methods)
    {
        foreach($methods as $key=>$value)
        {
            if(is_null($key) || $key == '')
                array_splice($methods, $key, 1);
        }
    }
}
