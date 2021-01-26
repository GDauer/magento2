<?php


namespace Itatiaia\KondutoCustom\Cron;


use Magento\Sales\Model\OrderFactory;
use Psr\Log\LoggerInterface;

class UpdateOrderStatus
{
    /**
     * @var OrderFactory
     */
    private $orderFactory;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct
    (
        OrderFactory $orderFactory,
        LoggerInterface $logger
    )
    {
        $this->orderFactory = $orderFactory;
        $this->logger = $logger;
    }

    public function execute()
    {
        $this->logger->info('================== ENTROU CRON UPDATE STATUS ==================', []);
        $orderCollection = $this->orderFactory->create()->getCollection()->addFieldToFilter('state', ['null' => true]);
        foreach ($orderCollection as $order) {
            $status = $order->getStatus();
            $this->logger->info('ORDER ID', [$order->getId()]);
            $this->logger->info('STATUS BEFORE', [$status]);
            if($status === 'aguardando_analise_manual' || $status === 'aguardando_pagamento') {
                $order->setState('new');
            } else if($status === 'faturado_sap') {
                $order->setState('processing');
            }
            $this->logger->info('STATUS AFTER', [$order->getState()]);

            $order->save();
        }
    }
}