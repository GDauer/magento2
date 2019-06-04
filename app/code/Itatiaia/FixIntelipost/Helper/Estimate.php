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

    function calculateEstimateDeliveryDate($orderNumber)
    {
        $order = $this->orderFactory->create()->loadByIncrementId($orderNumber);
        $intelipostQuote = $this->json->unserialize($order->getIntelipostQuote());

        $businessDays = $intelipostQuote['result']['quotes'][0]['delivery_estimated_delivery_business_day'];
        return $businessDays;

    }

}
