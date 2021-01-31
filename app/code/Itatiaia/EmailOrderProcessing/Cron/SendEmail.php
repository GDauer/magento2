<?php
namespace Itatiaia\EmailOrderProcessing\Cron;

class SendEmail
{
    private $transportBuilder;
    private $scopeConfig;
    private $storeManager;
    private $inlineTranslation;
    private $orderFactory;
    private $emailFlagFactory;
    private $logger;

    public function __construct(
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Itatiaia\EmailOrderProcessing\Model\EmailFlagFactory $emailFlagFactory,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->transportBuilder = $transportBuilder;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->inlineTranslation = $inlineTranslation;
        $this->orderFactory = $orderFactory;
        $this->emailFlagFactory = $emailFlagFactory;
        $this->logger = $logger;
    }

    public function execute()
    {
//        $this->logger->info('ORDERS EMAIL INIT');

        try {
            $last_day_before = date('Y-m-d H:i:s', strtotime('now - 48 hours'));
            $last_day = date('Y-m-d H:i:s', strtotime('now - 24 hours'));

            $orders = $this->orderFactory->create()->getCollection();

            $orders->getSelect()
                ->joinLeft(
                    array('email_flag' => $orders->getTable('itatiaia_emailorderprocessing_email_flag')),
                    'main_table.increment_id= email_flag.order_increment_id',
                    array(
                        'email_flag_sent_at' => 'email_flag.sent_at'
                    )
                );

            $orders->addFieldToFilter('state', 'processing');
            $orders->addFieldToFilter('status', 'processing');
            $orders->addFieldToFilter('created_at', [
                'gt' => $last_day_before,
            ]);
            $orders->addFieldToFilter('created_at', [
                'lt' => $last_day,
            ]);
            $orders->addFieldToFilter('email_flag.sent_at', ['null' => true]);

            $orders->load();

            // $this->logger->info('ORDERS EMAIL SQL', [(string)$orders->getSelect()]);
            // $this->logger->info('ORDERS EMAIL COUNT', [count($orders)]);

            foreach($orders as $order){
                $this->sendEmail($order);
                $this->setEmailFlag($order->getIncrementId());
            }

            // $this->logger->info('ORDERS SENT SUCCESS');
        } catch (\Exception $e){
            $this->logger->info('ORDERS ERROR SENT', [$e->getMessage()]);
        }
    }

    public function sendEmail($order)
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

        $variables = [
            'customer_name' => $order->getCustomerName(),
            'customer_email' => $order->getCustomerEmail(),
            'order_increment_id' => $order->getIncrementId(),
            'order_status' => $order->getStatusLabel()
        ];

        $sender = [
            'name' => $this->scopeConfig->getValue('trans_email/ident_general/name', $storeScope),
            'email' => $this->scopeConfig->getValue('trans_email/ident_general/email', $storeScope)
        ];

        $this->inlineTranslation->suspend();

        $transport = $this->transportBuilder->setTemplateIdentifier('order_processing_email_alert') // this code we have mentioned in the email_templates.xml
            ->setTemplateOptions([
                'area' => \Magento\Framework\App\Area::AREA_FRONTEND, // this is using frontend area to get the template file
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

    public function setEmailFlag($order_increment_id)
    {
        $emailFlag = $this->emailFlagFactory->create();
        $emailFlag->setOrderIncrementId($order_increment_id);
        $emailFlag->setSentAt(date('Y-m-d H:i:s'));
        return $emailFlag->save();
    }
}
