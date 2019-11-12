<?php

namespace Itatiaia\CustomMercadoPago\Model\Notifications\Topics;

use Magento\Sales\Model\Order;

class TopicsAbstract extends \MercadoPago\Core\Model\Notifications\Topics\Payment
{

    const STATUS = 'payment_review';

    public function setStatusAndComment($order, $newStatusOrder, $message)
    {

        if ($order->getState() !== Order::STATE_COMPLETE) {
            if ($newStatusOrder == 'canceled' && $order->getState() != 'canceled') {
                $order->cancel();
            } else {
                $order->setState($this->_getAssignedState($newStatusOrder));
            }
            $order->addStatusToHistory($newStatusOrder, $message, true);
        }

        $payment = $order->getPayment();
        $method = $payment->getMethod();

        if($order->getState() == 'new' && $method == 'mercadopago_customticket') {
            $order->setState(self::STATUS);
            $order->setStatus(self::STATUS);
        }

        return $order;

    }
}