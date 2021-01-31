<?php


namespace DigitalHub\EmailOrderStatus\Observer;


use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class SendEmail implements ObserverInterface
{

    private $transportBuilder;

    private $scopeConfig;

    private $storeManager;

    private $inlineTranslation;

    private $logger;

    public function __construct
    (
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Psr\Log\LoggerInterface $logger

    ) {
        $this->transportBuilder = $transportBuilder;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->inlineTranslation = $inlineTranslation;
        $this->logger = $logger;
    }

    public function execute(Observer $observer)
    {
        $order = $observer->getOrder();

        if($order->getState() == 'canceled') {
            try {
                $this->sendMail($order);
            } catch (\Exception $e)
            {
                $this->logger->info('ERROR', [$e->getMessage()]);
            }
        }
    }

    private function sendMail($order)
    {

//        $this->logger->info('SEND MAIL INIT');

        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

        $variables = [
            'customer_name' => $order->getCustomerName(),
            'customer_email' => $order->getCustomerEmail(),
            'order_increment_id' => $order->getIncrementId()
        ];

        $sender = [
            'name' => $this->scopeConfig->getValue('trans_email/ident_general/name', $storeScope),
            'email' => $this->scopeConfig->getValue('trans_email/ident_general/email', $storeScope)
        ];

        $this->inlineTranslation->suspend();

        $transport = $this->transportBuilder->setTemplateIdentifier('sales_email_order_canceled_template')
            ->setTemplateOptions([
                'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
            ])
            ->setTemplateVars($variables)
            ->setFrom($sender)
            ->addTo($order->getCustomerEmail())
            ->getTransport();

        $result = $transport->sendMessage();

        $this->inlineTranslation->resume();

        return $result;

    }
}