<?php


namespace Itatiaia\Mooble\Observer;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order\Invoice;
use Magento\Store\Model\ScopeInterface;
use Psr\Log\LoggerInterface;
use Magento\Checkout\Model\Session;

class PurchasePixel implements ObserverInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * PurchasePixel constructor.
     * @param LoggerInterface $logger
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        LoggerInterface $logger,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
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
            if (!empty($this->getPartnerName())) {
                try {
                    $client = new \GuzzleHttp\Client();
                    $response = $client->request(
                        'GET',
                        $this->getUrl($order)
                    );
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
    protected function getUrl($order) {
        return 'https://www.mooble1.com/purchasepixel/' . $this->getPartnerName() . '?order=' . $order->getIncrementId() . '&customer='
        . $order->getCustomerEmail() . '&total=' . $order->getGrandTotal();
    }

    /**
     * @return string|null
     */
    public function getPartnerName() {
        return $this->scopeConfig->getValue('mooble/general/partner_name',ScopeInterface::SCOPE_STORE);
    }
}