<?php
namespace Itatiaia\Custom\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    public function getInstallmentsConfig(){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $config = $objectManager->get('\Magento\Framework\App\Config\ScopeConfigInterface');
        $logger = $objectManager->get('\Psr\Log\LoggerInterface');

        $serializedConfig = $config->getValue('payment/adyen_cc/installments');
        $configData = unserialize($serializedConfig);
        return $configData;
    }
}
