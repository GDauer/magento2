<?php


namespace Itatiaia\Mooble\Observer;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order\Invoice;
use Magento\Store\Model\ScopeInterface;
use Psr\Log\LoggerInterface;
use Magento\Quote\Model\QuoteFactory;

class PurchasePixel implements ObserverInterface
{
    const SANDBOX_URL = 'https://demo.mooble.com/';
    const PRODUCTION_URL = 'https://www.mooble.com/';
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
     * PurchasePixel constructor.
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
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $invoice = $observer->getEvent()->getInvoice();
        if ($invoice->getState() == Invoice::STATE_PAID
            && $invoice->getOrigData('state') != Invoice::STATE_PAID) {
            $order = $invoice->getOrder();
            $quote = $this->quoteFactory->create()->load($order->getQuoteId());
            if (!empty($this->getPartnerName()) && !empty($quote->getMoobleId())) {
                try {
                    $client = new \GuzzleHttp\Client();
                    $response = $client->request(
                        'GET',
                        $this->getUrl($order, $quote)
                    );
                    $this->logger->info(__('Integrated order %1 with Mooble', $order->getIncrementId()), [$response]);
                } catch (\Exception $e) {
                    $this->logger->critical($e->getMessage());
                }
            }
        }
    }

    /**
     * @param $order
     * @return string
     */
    protected function getUrl($order, $quote) {

        if ($this->scopeConfig->getValue('mooble/general/sandbox',ScopeInterface::SCOPE_STORE)) {
            $baseUrl = self::SANDBOX_URL;
        } else {
            $baseUrl = self::PRODUCTION_URL;
        }

        return $baseUrl . 'purchasepixel/' . $this->getPartnerName() . '?order=' . $order->getIncrementId() . '&customer='
        . $order->getCustomerEmail() . '&total=' . $order->getGrandTotal() . '&budgetId=' . $quote->getMoobleId();
    }

    /**
     * @return string|null
     */
    public function getPartnerName() {
        return $this->scopeConfig->getValue('mooble/general/partner_name',ScopeInterface::SCOPE_STORE);
    }
}