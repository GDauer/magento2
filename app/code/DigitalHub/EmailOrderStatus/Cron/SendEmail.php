<?php


namespace DigitalHub\EmailOrderStatus\Cron;

use Magento\Sales\Model\OrderFactory;

class SendEmail
{
    private $transportBuilder;

    private $scopeConfig;

    private $storeManager;

    private $inlineTranslation;

    private $logger;
    /**
     * @var OrderFactory
     */
    private $orderFactory;

    public function __construct
    (
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Psr\Log\LoggerInterface $logger,
        OrderFactory $orderFactory


    ) {
        $this->transportBuilder = $transportBuilder;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->inlineTranslation = $inlineTranslation;
        $this->logger = $logger;
        $this->orderFactory = $orderFactory;
    }

    public function execute(){

//        $this->logger->info('CRON DE RECUPERAÇÃO', []);

        $last_day = date('Y-m-d H:i:s', strtotime('now - 24 hours'));
        $final_day = date('Y-m-d H:i:s', strtotime($last_day .  ' - 48 hours'));

        $orders = $this->orderFactory->create()->getCollection();

        $orders->getSelect()
            ->joinLeft(
                array('sales_order_payment' => $orders->getTable('sales_order_payment')),
                'main_table.entity_id= sales_order_payment.parent_id',
                array(
                    'payment_method' => 'sales_order_payment.method'
                )
            );

        $orders->addFieldToFilter('sales_order_payment.method', 'adyen_boleto');
        $orders->addFieldToFilter('created_at', ['gt' => $final_day]);
        $orders->addFieldToFilter('created_at', ['lteq' => $last_day]);
        $orders->addFieldToFilter('state', 'payment_review');
//        $orders->addFieldToFilter('increment_id', '000000407');
        $orders->load();

        foreach ($orders as $order)
        {
            $this->sendMail($order);
        }
    }

    public function sendMail($order)
    {

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

        $transport = $this->transportBuilder->setTemplateIdentifier('itatiaia_email_order_recuperar_boleto')
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