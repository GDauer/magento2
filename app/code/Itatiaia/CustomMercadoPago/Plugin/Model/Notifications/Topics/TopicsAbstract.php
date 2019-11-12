<?php

namespace Itatiaia\CustomMercadoPago\Plugin\Model\Notifications\Topics;

class TopicsAbstract
{

    const STATUS = 'payment_review';

    /**
     * @var Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory
     */
    private $collectionFactory;

    public function __construct
    (
        Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory $collectionFactory
    )
    {
        $this->collectionFactory = $collectionFactory;
    }

    public function afterAetStatusAndComment(\MercadoPago\Core\Model\Notifications\Topics\TopicsAbstract $subject, $order)
    {
        $payment = $order->getPayment();
        $method = $payment->getMethodInstance();
        if($order->getState() == 'new' && $method == 'mercadopago_customticket') {
            $statusObject = $this->collectionFactory->create()
                ->addFieldToFilter('main_table.status', self::STATUS)
                ->addFieldToFilter('state_table.is_default', true)
                ->joinStates()
                ->getFirstItem();

            $order->setState(self::STATUS);
            $order->setStatus($statusObject->getStatus());
        }

        return [$order];
    }
}