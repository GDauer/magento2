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
}