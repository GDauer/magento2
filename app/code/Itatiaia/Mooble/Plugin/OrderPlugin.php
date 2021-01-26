<?php


namespace Itatiaia\Mooble\Plugin;


use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Psr\Log\LoggerInterface;
use Magento\Sales\Model\Order;
use Magento\Quote\Model\QuoteFactory;

class OrderPlugin
{
    const SANDBOX_URL = 'https://demo.mooble.com/';
    const PRODUCTION_URL = 'https://www.mooble.com/';
    const INCREMENT_ID_SUFFIX = 'MO';

    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;
    /**
     * @var QuoteFactory
     */
    protected $quoteFactory;

    /**
     * CancelOrderPlugin constructor.
     * @param LoggerInterface $logger
     * @param ScopeConfigInterface $scopeConfig
     * @param QuoteFactory $quoteFactory
     */
    public function __construct(
        LoggerInterface $logger,
        ScopeConfigInterface $scopeConfig,
        QuoteFactory $quoteFactory
    ) {
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
        $this->quoteFactory = $quoteFactory;
    }

    /**
     * @param Order $order
     * @param $result
     * @return mixed
     */
    public function afterCancel(Order $order, $result)
    {
        $quote = $this->quoteFactory->create()->load($order->getQuoteId());
        if (!empty($this->getPartnerName()) && !empty($quote->getMoobleId())) {
            try {
                $client = new \GuzzleHttp\Client();
                $response = $client->request(
                    'POST',
                    $this->getUrl($order)
                );
                $this->logger->info(__('Canceled order %1 in Mooble', $order->getIncrementId()), [$response]);
            } catch (\Exception $e) {
                $this->logger->critical($e->getMessage());
            }
        }

        return $result;
    }

    /**
     * @param Order $order
     */
    public function beforeSave(Order $order)
    {
        $quote = $this->quoteFactory->create()->load($order->getQuoteId());
        $incrementId = $order->getIncrementId();
        if (!empty($quote->getMoobleId()) && strpos($incrementId, self::INCREMENT_ID_SUFFIX) === false) {
            $incrementId .= self::INCREMENT_ID_SUFFIX;
            $order->setIncrementId($incrementId);
        }
        return;
    }

    /**
     * @param $order
     * @return string
     */
    protected function getUrl($order) {
        if ($this->scopeConfig->getValue('mooble/general/sandbox',ScopeInterface::SCOPE_STORE)) {
            $baseUrl = self::SANDBOX_URL;
        } else {
            $baseUrl = self::PRODUCTION_URL;
        }

        return $baseUrl . 'api/ecommerce/cancelorder?partnership=' . $this->getPartnerName() . '&order=' . $order->getIncrementId();
    }

    /**
     * @return string|null
     */
    public function getPartnerName() {
        return $this->scopeConfig->getValue('mooble/general/partner_name',ScopeInterface::SCOPE_STORE);
    }
}
