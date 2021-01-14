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

        if(!$order)
        {
            $this->_logger->info('Order not found - getCustomLastOrderId');
            $order = $this->_orderFactory->create()->load($this->_checkoutSession->getCustomLastOrderId());
        }

        return $order;
    }
}