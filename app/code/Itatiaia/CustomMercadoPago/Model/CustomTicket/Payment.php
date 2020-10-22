<?php

namespace Itatiaia\CustomMercadoPago\Model\CustomTicket;

class Payment extends \MercadoPago\Core\Model\CustomTicket\Payment
{
    /**
     * Return tickets options availables
     *
     * @return array
     */
    public function getTicketsOptions()
    {
        $excludePaymentMethods = $this->_scopeConfig->getValue(\Itatiaia\CustomMercadoPago\Helper\ConfigData::PATH_CUSTOM_EXCLUDE_PAYMENT_METHODS, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $listExclude = explode(",", $excludePaymentMethods);

        $payment_methods = $this->_coreModel->getPaymentMethods();
        $tickets = array();

        foreach ($payment_methods['response'] as $pm)
        {
            if ($pm['payment_type_id'] == "ticket" || $pm['payment_type_id'] == "atm")
            {
                if(!in_array($pm['id'], $listExclude))
                {
                    $tickets[] = $pm;
                }
            }
        }

        $paymentlimitActive = (bool) $this->_scopeConfig->getValue(\Itatiaia\CustomMercadoPago\Helper\ConfigData::PATH_CUSTOM_TICKET_LIMIT_ACTIVE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        if($paymentlimitActive)
        {
            $validatePaymentMethods = $this->_scopeConfig->getValue(\Itatiaia\CustomMercadoPago\Helper\ConfigData::PATH_CUSTOM_TICKET_LIMIT_PAYMENT_METHODS, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $listInclude = explode(",", $validatePaymentMethods);
            $limitAmount = (float) $this->_scopeConfig->getValue(\Itatiaia\CustomMercadoPago\Helper\ConfigData::PATH_CUSTOM_TICKET_LIMIT_AMOUNT, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $quoteSubtotal = (float) $this->_getQuote()->getSubtotal();

            foreach ($tickets as $key => $ticket)
            {
                if(in_array($ticket['id'], $listInclude) && $quoteSubtotal > $limitAmount) {
                    array_splice($tickets, $key, 1);
                }
            }
        }

        return $tickets;
    }
}