<?php

namespace Itatiaia\CustomMercadoPago\Controller\Checkout;

/**
 * Class Page
 * @package MercadoPago\Core\Controller\Checkout
 */
class Page extends \MercadoPago\Core\Controller\Checkout\Page
{

    /**
     * @return mixed
     */
    protected function _getOrder()
    {
        $order = $this->_checkoutSession->getLastRealOrder();
        if(!$this->_checkoutSession->getLastRealOrder()){
            $orderId = $this->_checkoutSession->getCustomLastOrderId();
            $this->_helperData->log('ORDER: ' .$orderId, 'mercadopago.log');
            $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
        }
        $this->_helperData->log('ORDER: ' .$order->getEntityId(), 'mercadopago.log');
        return $order;
    }

    /**
     * Return handle name, depending on payment method used in the order placed
     *
     * @return string
     */
    public function getCheckoutHandle()
    {
        $handle = '';
        $order = $this->_getOrder();
        $this->_helperData->log('ORDER: --', 'mercadopago.log');
        if (!empty($order->getId())) {
            $this->_helperData->log('ORDER: ' .$order->getId() . ' | ' .$order->getEntityId(), 'mercadopago.log');
            $this->_helperData->log('ORDER payment method: ' .$order->getPayment()->getMethod(), 'mercadopago.log');
            $handle = $order->getPayment()->getMethod();
        }
        $handle .= '_success';
        $this->_helperData->log('ORDER: handle ' .$handle, 'mercadopago.log');

        return $handle;
    }
}
