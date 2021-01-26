<?php
namespace Itatiaia\FixIntelipost\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Sales\Model\OrderFactory;

class Estimate extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * @var OrderFactory
     */
    protected $orderFactory;

    protected $scopeConfig;
    /**
     * @var Json
     */
    protected $json;

    public function __construct(
        Json $json,
        Context $context,
        ScopeConfigInterface $scopeConfig,
        OrderFactory $orderFactory
    ) {
        parent::__construct($context);
        $this->orderFactory = $orderFactory;
        $this->scopeConfig = $scopeConfig;
        $this->json = $json;
    }

    function getBusinessDays($orderNumber)
    {
        $order = $this->orderFactory->create()->loadByIncrementId($orderNumber);
        $intelipostQuote = $this->json->unserialize($order->getIntelipostQuote());

        $businessDays = $intelipostQuote['result']['quotes'][0]['delivery_estimated_delivery_business_day'];

        $invoice = $order->getInvoiceCollection()->getFirstItem();

        $finalData = $this->calculateFinalDate($invoice->getCreatedAt(),$businessDays);
        return $finalData;
    }


    function getDate($businessDays)
    {
        $finalData = $this->calculateFinalDate(date('Y-m-d'),$businessDays);
        return $finalData;
    }


    function calculateFinalDate($startDate,$days){

        $storeWorkingDays = $this->getStoreWorkingDays();
        $holidays = $this->getHolidays();

        $daysToAdd = $days;

        $diasUteis = 0;
        $i = 0;

        while($diasUteis < $days){

            $currentDay = strtotime($startDate . ' +' . $i . 'days');
            $currentDate = date('d/m/Y', $currentDay);
            $current = date("N", $currentDay);

            if(!in_array($current, $storeWorkingDays) || in_array($currentDate,$holidays)){
                $daysToAdd++;
            } else {
                $diasUteis++;
            }

            $i++;
        }

        $finalDay = strtotime($startDate . '+ ' . $daysToAdd . "days");
        $date = date('Y-m-d', $finalDay);

        return $date;
    }

    public function getStoreWorkingDays()
    {
        $days = $this->json->unserialize($this->scopeConfig->getValue('intelipost_push/holidays_table/business_days', \Magento\Store\Model\ScopeInterface::SCOPE_STORE));
        $result = [];

        foreach ($days as $day)
        {
            foreach ($day as $key => $value) {
                $result[] = $value;
            }
        }
        return $result;
    }

    public function getHolidays()
    {
        $days = $this->json->unserialize($this->scopeConfig->getValue('intelipost_push/holidays_table/business_days_disabled', \Magento\Store\Model\ScopeInterface::SCOPE_STORE));
        $result = [];

        foreach ($days as $day)
        {
            foreach ($day as $key => $value) {
                $result[] = $value;
            }
        }
        return $result;
    }

}
