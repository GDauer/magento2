<?php

namespace Itatiaia\CustomMercadoPago\Model\Custom;

class Payment extends \MercadoPago\Core\Model\Custom\Payment
{
    /**
     * Check refund availability
     *
     * @return bool
     * @api
     * @deprecated 100.2.0
     */
    public function canRefund()
    {
        return $this->_canRefund;
    }

    public function refund(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        if (!$this->canRefund()) {
            throw new \Magento\Framework\Exception\LocalizedException(__('The refund action is not available.'));
        }
        return $this;
    }
}