<?php


namespace Itatiaia\KondutoCustom\Plugin;

use Konduto\Antifraud\Helper\Data;
use Psr\Log\LoggerInterface;
use Konduto\Antifraud\Model\QueueRepository;

class IgnoreCanceled
{
    /** @var Data */
    protected $kondutoDataHelper;

    /** @var LoggerInterface */
    protected $logger;

    /** @var QueueRepository */
    protected $queueRepository;

    /**
     * IgnoreCanceled constructor.
     * @param Data $kondutoDataHelper
     */
    public function __construct(
        Data $kondutoDataHelper,
        LoggerInterface $logger,
        QueueRepository $queueRepository
    )
    {
        $this->kondutoDataHelper = $kondutoDataHelper;
        $this->logger = $logger;
        $this->queueRepository = $queueRepository;
    }

    /**
     * Does not allow canceled orders to be submitted for anti fraud analysis.
     * @param \Konduto\Antifraud\Model\KondutoService $subject
     * @param callable $proceed
     * @param $orderId
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function aroundAnalysis(\Konduto\Antifraud\Model\KondutoService $subject, callable $proceed, $orderId)
    {
        $this->logger->info("PLUGIN - Checking order status before send to Konduto");
        $order = $this->kondutoDataHelper->loadOrder($orderId);
        $payment = $order->getPayment();
        $additional = $payment->getAdditionalInformation();
        if(!$additional['card_expiration_month'] || !$additional['card_expiration_year']) {
            $additional['card_expiration_month'] = $additional['paymentResponse']['card']['expiration_month'];
            $additional['card_expiration_year'] = $additional['paymentResponse']['card']['expiration_year'];
            $payment->setAdditionalInformation($additional);
            $payment->save();
        }
        if($order->getStatus() == 'canceled') {
            $orderIncrementId = $order->getIncrementId();
            $this->logger->info("Cancelled Order not sended to Konduto. [Order Id = $orderId] [Increment Id = $orderIncrementId]");
            $this->queueRepository->deleteById($orderId);
            return;
        }
        $result = $proceed($orderId);
        return $result;
    }
}