<?php


namespace Itatiaia\CustomMercadoPago\Cron\Order;

use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;

class ExpiredOrderCanceller
{
    protected $orderCollectionFactory;

    /**
     * ExpiredOrderCanceller constructor.
     * @param $orderCollectionFactory
     */
    public function __construct(OrderCollectionFactory $orderCollectionFactory)
    {
        $this->orderCollectionFactory = $orderCollectionFactory;
    }

    public function execute()
    {
        $expiredOrders = $this->getExpiredOrders();
    }

    private function getExpiredOrders()
    {
        $collection = $this->orderCollectionFactory->create()
            ->addFieldToSelect("*")
            ->addAttributeToFilter('status', ['in' => ['pending']])
            ->addExpressionFieldToSelect('elapsed_hours', 'TIMESTAMPDIFF(HOUR, created_at, NOW())', ['created_at']);

        $collection->getSelect()->joinLeft(['payment_table' => 'sales_order_payment'],
            'main_table.entity_id = payment_table.parent_id', ['method', 'additional_information'], null);
        $collection->addAttributeToFilter('payment_table.method', ['mercadopago_customticket', 'mercadopago_basic']);
        $collection->getSelect()->having('elapsed_hours >=48');

        return $collection->getData();
    }
}