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

namespace Itatiaia\CustomMercadoPago\Model\CustomTicket;

class Payment extends \MercadoPago\Core\Model\CustomTicket\Payment
{
    const PAYMENT_TYPE_TICKET = 'ticket';
    const PAYMENT_TYPE_ATM = 'atm';

    /**
     * Return tickets options availables
     *
     * @return array
     */
    public function getTicketsOptions(): array
    {
        $excludePaymentMethods = $this->_scopeConfig->getValue(\Itatiaia\CustomMercadoPago\Helper\ConfigData::PATH_CUSTOM_EXCLUDE_PAYMENT_METHODS, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $listExclude = [];
        if(!is_null($excludePaymentMethods))
            $listExclude = explode(",", $excludePaymentMethods);

        $payment_methods = $this->_coreModel->getPaymentMethods();
        $tickets = array();

        foreach ($payment_methods['response'] as $pm)
        {
            if ($pm['payment_type_id'] == self::PAYMENT_TYPE_TICKET || $pm['payment_type_id'] == self::PAYMENT_TYPE_ATM)
            {
                if(!in_array($pm['id'], $listExclude))
                {
                    $tickets[] = $pm;
                }
            }
        }

        $this->validatePaymentMethods($tickets);

        return $tickets;
    }

    /**
     *
     * @param array &$tickets
     */
    public function validatePaymentMethods(&$tickets)
    {
        $paymentlimitActive = (bool) $this->_scopeConfig->getValue(\Itatiaia\CustomMercadoPago\Helper\ConfigData::PATH_CUSTOM_TICKET_LIMIT_ACTIVE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        if($paymentlimitActive)
        {
            $validatePaymentMethods = $this->_scopeConfig->getValue(\Itatiaia\CustomMercadoPago\Helper\ConfigData::PATH_CUSTOM_TICKET_LIMIT_PAYMENT_METHODS, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $listInclude = [];
            if(!is_null($validatePaymentMethods))
                $listInclude = explode(",", $validatePaymentMethods);

            $limitAmount = (float) $this->_scopeConfig->getValue(\Itatiaia\CustomMercadoPago\Helper\ConfigData::PATH_CUSTOM_TICKET_LIMIT_AMOUNT, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $subtotalWithShipping = $this->getTotalWithShipping();


            foreach ($tickets as $key => $ticket)
            {
                if(in_array($ticket['id'], $listInclude) && $subtotalWithShipping > $limitAmount) {
                    array_splice($tickets, $key, 1);
                }
            }
        }
    }

    /**
     * Return total amount with shipping
     *
     * @return float
     */
    private function getTotalWithShipping(): float
    {
        $quoteSubtotal = (float) $this->_getQuote()->getSubtotal();
        $shippingAmount = $this->getMaxShippingAmount();

        return (float) ($quoteSubtotal + $shippingAmount);
    }

    /**
     * Return max shipping amount value
     *
     * @return float
     */
    private function getMaxShippingAmount(): float
    {
        $shippingAmount = (float) $this->_getQuote()->getShippingAddress()->getShippingAmount();
        $method = $this->_getQuote()->getShippingAddress()->getShippingMethod();

        if(is_null($method) && $shippingAmount == 0)
        {
            $quote = $this->_getQuote();
            $quote->getShippingAddress()->setCountryId('*');
            $quote->collectTotals();
            $quote->getShippingAddress()->setCollectShippingRates(1);
            $quote->getShippingAddress()->collectShippingRates();
            $_rates = $quote->getShippingAddress()->getShippingRatesCollection();

            $shippingRates = [];
            foreach ($_rates as $_rate)
            {
                $shippingRates[] =  $_rate->getPrice();
            }

            $shippingAmount = (float) max($shippingRates);
        }

        return $shippingAmount;
    }

    public function initialize($paymentAction, $stateObject)
    {
        try {
//            $this->_helperData->log("CustomPaymentTicket::initialize - Ticket: init prepare post payment", self::LOG_NAME);
            $quote = $this->_getQuote();
            $order = $this->getInfoInstance()->getOrder();
            $payment = $order->getPayment();

            $payment_info = array();

            if ($payment->getAdditionalInformation("coupon_code") != "") {
                $payment_info['coupon_code'] = $payment->getAdditionalInformation("coupon_code");
            }

            $preference = $this->_coreModel->makeDefaultPreferencePaymentV1($payment_info, $quote, $order);

            $preference['payment_method_id'] = $payment->getAdditionalInformation("payment_method");

            if ($payment->getAdditionalInformation("firstName") != "") {
                $preference['payer']['first_name'] = $payment->getAdditionalInformation("firstName");
            }
            if ($payment->getAdditionalInformation("lastName") != "") {
                $preference['payer']['last_name'] = $payment->getAdditionalInformation("lastName");
            }
            if ($payment->getAdditionalInformation("docType") != "") {
                $preference['payer']['identification']['type'] = $payment->getAdditionalInformation("docType");
                //remove last-name pessoa juridica
                if ($preference['payer']['identification']['type'] == "CNPJ") {
                    $preference['payer']['last_name'] = "";
                }
            }

            if ($payment->getAdditionalInformation("docNumber") != "") {
                $preference['payer']['identification']['number'] = $payment->getAdditionalInformation("docNumber");
            }
            if ($payment->getAdditionalInformation("address") != "") {
                $preference['payer']['address']['street_name'] = $payment->getAdditionalInformation("address");
            }
            if ($payment->getAdditionalInformation("addressNumber") != "") {
                $preference['payer']['address']['street_number'] = $payment->getAdditionalInformation("addressNumber");
            }
            if ($payment->getAdditionalInformation("addressCity") != "") {
                $preference['payer']['address']['city'] = $payment->getAdditionalInformation("addressCity");
                $preference['payer']['address']['neighborhood'] = $payment->getAdditionalInformation("addressCity");
            }
            if ($payment->getAdditionalInformation("addressState") != "") {
                $preference['payer']['address']['federal_unit'] = $payment->getAdditionalInformation("addressState");
            }
            if ($payment->getAdditionalInformation("addressZipcode") != "") {
                $preference['payer']['address']['zip_code'] = $payment->getAdditionalInformation("addressZipcode");
            }

//            $this->_helperData->log("CustomPaymentTicket::initialize - Preference to POST", 'mercadopago-custom.log', $preference);
        } catch (\Exception $e) {
//            $this->_helperData->log("CustomPaymentTicket::initialize - There was an error retrieving the information to create the payment, more details: " . $e->getMessage());
            throw new \Magento\Framework\Exception\LocalizedException(__(\MercadoPago\Core\Helper\Response::PAYMENT_CREATION_ERRORS['INTERNAL_ERROR_MODULE']));
            return $this;
        }

        // POST /v1/payments
        $response = $this->_coreModel->postPaymentV1($preference);
//        $this->_helperData->log("CustomPaymentTicket::initialize - POST /v1/payments RESPONSE", self::LOG_NAME, $response);

        if (isset($response['status']) && ($response['status'] == 200 || $response['status'] == 201)) {

            $payment = $response['response'];

            $this->getInfoInstance()->setAdditionalInformation("paymentResponse", $payment);

            return true;

        } else {

            $messageErrorToClient = $this->_coreModel->getMessageError($response);

            $arrayLog = array(
                "response" => $response,
                "message" => $messageErrorToClient
            );

//            $this->_helperData->log("CustomPaymentTicket::initialize - The API returned an error while creating the payment, more details: " . json_encode($arrayLog));

            throw new \Magento\Framework\Exception\LocalizedException(__($messageErrorToClient));

            return $this;
        }
    }
}