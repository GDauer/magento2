<?php
namespace Itatiaia\AllinIntegration\Observer;

class SendCustomerEmail implements \Magento\Framework\Event\ObserverInterface
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
        $customer = $observer->getCustomer();
        $isActive = (int)$this->scopeConfig->getValue('newsletter/itatiaia_allinintegration/active', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        if($isActive){
            $subscriber = $this->subscriberFactory->create();
            $subscriber->loadByEmail($customer->getEmail());

            if($subscriber->isSubscribed()){
                $customerName = $customer->getFirstName() . ' ' . $customer->getLastname();
                $customerEmail = $customer->getEmail();

                $this->helper->sendCustomer($customerEmail, $customerName);
            }
        }
    }
}
