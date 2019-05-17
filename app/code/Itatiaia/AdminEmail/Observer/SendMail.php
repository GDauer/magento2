<?php

namespace Itatiaia\AdminEmail\Observer;

use Magento\Framework\Event\Observer;

class SendMail implements \Magento\Framework\Event\ObserverInterface
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

        $this->logger->info('OBSERVER INIT');

        $order = $observer->getEvent()->getOrder();

        $orderItems = $order->getAllVisibleItems();

        $hasIron = false;
        $hasWood = false;

        foreach ($orderItems as $item)
        {
            if($item->getProduct()->getMaterialPredominante() == '270')
            {
                $hasIron = true;
            }
            if($item->getProduct()->getMaterialPredominante() == '271')
            {
                $hasWood = true;
            }
        }

        $this->logger->info('OBSERVER AFTER');

        if($hasIron && $hasWood)
        {
            $this->sendMail($order);
        }
    }

    private function sendMail($order)
    {

        $this->logger->info('SEND MAIL INIT');

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

        $transport = $this->transportBuilder->setTemplateIdentifier('iron_wood_email_alert')
        ->setTemplateOptions([
            'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
            'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
        ])
            ->setTemplateVars($variables)
            ->setFrom($sender)
            ->addTo($sender['email'])
            ->getTransport();

        $result = $transport->sendMessage();

        $this->inlineTranslation->resume();

        return $result;

    }

}