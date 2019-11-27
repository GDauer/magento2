<?php


namespace Itatiaia\KondutoCustom\Plugin;

use Konduto\Antifraud\Helper\Data;
use Psr\Log\LoggerInterface;

class IgnoreCanceled
{
    /** @var Data */
    protected $kondutoDataHelper;

    /** @var LoggerInterface */
    protected $logger;

    /**
     * IgnoreCanceled constructor.
     * @param Data $kondutoDataHelper
     */
    public function __construct(
        Data $kondutoDataHelper,
        LoggerInterface $logger
    )
    {
        $this->kondutoDataHelper = $kondutoDataHelper;
        $this->logger = $logger;
    }

    /**
     * Does not allow canceled orders to be submitted for anti fraud analysis.
     * @param \Konduto\Antifraud\Model\KondutoService $subject
     * @param callable $proceed
     * @param $orderId
     */
    public function aroundAnalysis(\Konduto\Antifraud\Model\KondutoService $subject, callable $proceed, $orderId)
    {
        $this->logger->info("PLUGIN - Checking order status before send to Konduto");
        $order = $this->kondutoDataHelper->loadOrder($orderId);
        if($order->getStatus() == 'canceled') {
            $orderIncrementId = $order->getIncrementId();
            $this->logger->info("Cancelled Order not sended to Konduto. [Order Id = $orderId] [Increment Id = $orderIncrementId]");
            return;
        }
        $result = $proceed($orderId);
        return $result;
    }
}