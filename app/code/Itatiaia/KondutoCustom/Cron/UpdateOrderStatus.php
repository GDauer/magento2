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

    public function __construct(
        OrderFactory $orderFactory,
        LoggerInterface $logger
    ) {
        $this->orderFactory = $orderFactory;
        $this->logger = $logger;
    }

    public function execute()
    {
        $this->logger->info('================== ENTROU CRON UPDATE STATUS ==================', []);
        $orderCollection = $this->orderFactory->create()
            ->getCollection()
            ->addFieldToFilter(
                'state',
                ['or' => [
                    ['new'],
                    ['null' => true]
                ]]
            );
        foreach ($orderCollection as $order) 
        {

            $status = $order->getStatus();
            $state = $order->getState() ? $order->getState() : 'default';

            $this->logger->info('ORDER ID', [$order->getId()]);
            $this->logger->info('STATUS BEFORE', [$status]);
            $this->logger->info('STATE BEFORE', [$order->getState()]);

            $mapping = [
                "new" => [
                    "faturado_sap" => 'processing'
                ],
                'default' => [
                    "aguardando_analise_manual" => 'new',
                    'aguardando_pagamento' => 'new',
                    "faturado_sap" => 'processing'
                ]
            ];

            if (isset($mapping[$state][$status]))
            {
                $this->logger->info('CHANGING STATE FROM ' .$order->getState() .' TO '. $mapping[$state][$status]);
                $order->setState($mapping[$state][$status]);
                $order->save();
                return;
            }

            $this->logger->info('STATE NOT CHANGE');
        }
    }
}
