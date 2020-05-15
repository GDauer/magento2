<?php


namespace Itatiaia\CustomMercadoPago\Cron\Order;

use Magento\Framework\App\State;
use Magento\Framework\App\Area;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use Magento\Sales\Model\OrderFactory\Proxy as OrderFactoryProxy;
use Magento\Setup\Exception;
use Psr\Log\LoggerInterface;
use DateTime;
use DateInterval;
use Itatiaia\FixIntelipost\Helper\Estimate;

class ExpiredOrderCanceller
{
    const DEFAULT_EXPIRATION_DAYS = 7;
    const DEFAULT_EXPIRATION_HOURS = 168;

    /** @var OrderCollectionFactory */
    protected $orderCollectionFactory;

    /** @var OrderFactoryProxy */
    protected $orderFactory;

    /** @var OrderRepositoryInterface */
    protected $orderRepository;

    /** @var State */
    protected $state;

    /** @var SerializerInterface */
    protected $serializer;

    /** @var LoggerInterface */
    protected $logger;

    /** @var array */
    protected $disabledDates;

    /**
     * ExpiredOrderCanceller constructor.
     * @param OrderCollectionFactory $orderCollectionFactory
     * @param OrderFactoryProxy $orderFactory
     * @param OrderRepositoryInterface $orderRepository
     * @param State $state
     * @param SerializerInterface $serializer
     * @param LoggerInterface $logger
     * @param Estimate $intelipostHelper
     * @throws LocalizedException
     */
    public function __construct(
        OrderCollectionFactory $orderCollectionFactory,
        OrderFactoryProxy $orderFactory,
        OrderRepositoryInterface $orderRepository,
        State $state,
        SerializerInterface $serializer,
        LoggerInterface $logger,
        Estimate $intelipostHelper
    ) {
        $this->state = $state;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->orderFactory = $orderFactory;
        $this->orderRepository = $orderRepository;
        $this->serializer = $serializer;
        $this->logger = $logger;
        $this->disabledDates = $intelipostHelper->getHolidays();
    }

    /**
     * Execute
     */
    public function execute()
    {
        try {
            $this->state->setAreaCode(Area::AREA_CRONTAB);
        } catch (\Exception $e) {
            $this->logger->info(__("Area code already set. Executing routine..."));
        }

        $orders = $this->getOrders();
        $this->logger->info(__("MercadoPago - Starting expired ticket orders cancellation routine..."));
        foreach ($orders as $order) {
            try {
                $expirationDate = $this->getCompensationExpirationDate($order);
                $today = new DateTime();

                if ($today > $expirationDate) {
                    $this->logger->info(__("Trying to cancel order %1. Today: %2 Expiration Date: %3", $order["increment_id"], $today->format('d/m/Y'), $expirationDate->format('d/m/Y')));
                    $this->cancelOrder($order["increment_id"]);
                }
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
            }
        }
        $this->logger->info(__("MercadoPago - Finished expired ticket orders cancellation routine."));
    }

    /**
     * Cancel Order
     *
     * @param $incrementId
     * @throws LocalizedException
     */
    private function cancelOrder($incrementId)
    {
        try {
            $order = $this->orderFactory->create()->loadByIncrementId($incrementId);
            $order->cancel();
            $this->orderRepository->save($order);
            $message = __("Order canceled due to payment expiration date.");
            $order->addStatusToHistory("canceled", $message, true);
            $this->logger->info(__("Order %1 cancelled successfully.", $incrementId));
            return;
        } catch (\Exception $e) {
            throw new LocalizedException(__("Error trying to cancel Order %1. Reason %2", $incrementId,
                $e->getMessage()));
        }
    }

    /**
     * Get the ticket compensation expiration date
     *
     * @param $order
     * @return DateTime
     * @throws LocalizedException
     */
    private function getCompensationExpirationDate($order)
    {
        try {
            $info = $this->serializer->unserialize($order['additional_information']);
            $paymentInfo = $info["paymentResponse"] ?? null;

            if (!$paymentInfo) {
                throw new LocalizedException(__("MercadoPago Payment information not found for Order %1.",
                    $order["increment_id"]));
            }

            $dateCreated = $paymentInfo["date_created"] ? new DateTime($paymentInfo["date_created"]) : null;

            if (!$dateCreated) {
                $dateCreated = new DateTime($order["created_at"]);
            }

            $expiredDate = $this->getExpirationDate($dateCreated, self::DEFAULT_EXPIRATION_DAYS);

            return $expiredDate;
        } catch (\Exception $e) {
            throw new LocalizedException(__("Unable to generate order payment expiration date. Reason: %1",
                $e->getMessage()));
        }
    }

    /**
     * Generate an expiration date excluding weekends and invalid dates
     *
     * @param DateTime $initialDate
     * @param $daysToAdd
     * @return DateTime
     * @throws \Exception
     */
    private function getExpirationDate(DateTime $initialDate, $daysToAdd)
    {
        $finalDate = clone $initialDate;
        $interval  = "P" . $daysToAdd . "D";

        $finalDate->add(new DateInterval($interval));

        return $finalDate;
    }

    /**
     * Check if a date fall on weekend
     *
     * @param $dateTime
     * @return bool
     */
    private function isWeekend($dateTime)
    {
        $date = $dateTime->format("Y-m-d");
        return (date('N', strtotime($date)) >= 6);
    }

    /**
     * Get Orders related to MercadoPago ticket payment
     * @return array
     */
    private function getOrders()
    {
        $collection = $this->orderCollectionFactory->create()
            ->addFieldToSelect("*")
            ->addAttributeToFilter('status', ['in' => ['pending', 'aguardando_analise_manual', 'payment_review']])
            ->addExpressionFieldToSelect('elapsed_hours', 'TIMESTAMPDIFF(HOUR, created_at, NOW())', ['created_at']);

        $collection->getSelect()->joinLeft(['payment_table' => 'sales_order_payment'],
            'main_table.entity_id = payment_table.parent_id', ['method', 'additional_information'], null);
        $collection->addAttributeToFilter('payment_table.method', ['mercadopago_customticket']);
        $collection->getSelect()->having("elapsed_hours >=" . self::DEFAULT_EXPIRATION_HOURS);
        return $collection->getData();
    }
}