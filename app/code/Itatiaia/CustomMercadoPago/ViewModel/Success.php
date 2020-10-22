<?php

namespace Itatiaia\CustomMercadoPago\ViewModel;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class Success implements ArgumentInterface
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var Json
     */
    private $json;

    public function __construct
    (
        ScopeConfigInterface $scopeConfig,
        Json $json
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->json = $json;
    }

    public function getCustomResponse($status_detail)
    {
        if($this->isActive()) {
            $configValue = $this->scopeConfig->getValue('payment/mercadopago_configurations/custom_responses');
            if($configValue) {
                $custom_responses = $this->json->unserialize($configValue);
                foreach($custom_responses as $response) {
                    if($response['status'] === $status_detail) {
                        return $response['response'];
                    }
                }
            }
        }
        return null;
    }

    public function isActive()
    {
        $isActive = $this->scopeConfig->isSetFlag('payment/mercadopago_configurations/custom_responses_active');
        return $isActive;
    }

}