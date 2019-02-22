<?php
namespace Itatiaia\AllinIntegration\Observer;

class SendNewsletterSubscribeEmail implements \Magento\Framework\Event\ObserverInterface
{
    private $subscriberFactory;
    private $logger;
    private $scopeConfig;
    private $helper;

    public function __construct
    (
        \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Itatiaia\AllinIntegration\Helper\Data $helper
    )
    {
        $this->subscriberFactory = $subscriberFactory;
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
        $this->helper = $helper;
    }


    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        $subscriber = $observer->getSubscriber();
        $isActive = (int)$this->scopeConfig->getValue('newsletter/itatiaia_allinintegration/active', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        if($isActive){
            $this->logger->info('SUBSCRIBER', [$subscriber->getData()]);

            if($subscriber->isSubscribed()){
                $customerEmail = $subscriber->getEmail();
                $this->helper->sendCustomerEmail($customerEmail);
            }
        }
    }
}
