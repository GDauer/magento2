<?php

namespace Itatiaia\KondutoCustom\Helper;

use Konduto\Antifraud\Logger\Logger;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Stdlib\DateTime;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Sales\Api\Data\TransactionSearchResultInterfaceFactory;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\ResourceModel\Order\Payment\Transaction\CollectionFactory;
use \Konduto\Antifraud\Helper\Data as OriginalData;

/**
 * Class Data
 * @package Konduto\Antifraud\Helper
 */
class Data extends OriginalData
{
    /** @var DateTime\DateTime $date */
    private $date;

    /**
     * Data constructor.
     * @param OrderRepositoryInterface $orderRepository
     * @param ScopeConfigInterface $scopeConfig
     * @param CustomerRepositoryInterface $customerRepositoryInterface
     * @param TransactionSearchResultInterfaceFactory $transactionSearchResultInterfaceFactory
     * @param CollectionFactory $collectionFactory
     * @param TimezoneInterface $timezone
     * @param Logger $logger
     * @param DateTime\DateTime $date
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        ScopeConfigInterface $scopeConfig,
        CustomerRepositoryInterface $customerRepositoryInterface,
        TransactionSearchResultInterfaceFactory $transactionSearchResultInterfaceFactory,
        CollectionFactory $collectionFactory,
        TimezoneInterface $timezone,
        Logger $logger,
        \Magento\Framework\Stdlib\DateTime\DateTime $date
    ) {
        parent::__construct(
            $orderRepository,
            $scopeConfig,
            $customerRepositoryInterface,
            $transactionSearchResultInterfaceFactory,
            $collectionFactory,
            $timezone,
            $this->logger = $logger
        );
        $this->date = $date;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getNowDateTime()
    {
        return $this->date->gmtDate();
    }
}
