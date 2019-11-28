<?php


namespace Itatiaia\CustomMercadoPago\Plugin\Model;

use Magento\Backend\Model\UrlInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class CorePlugin
{
    protected $backendUrl;
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(
        UrlInterface $backendUrl,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->backendUrl = $backendUrl;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Setting capture as false
     *
     * @param \MercadoPago\Core\Model\Core $subject
     * @param $preference
     * @return array
     */
    public function beforePostPaymentV1(\MercadoPago\Core\Model\Core $subject, $preference)
    {
        if (!isset($preference['capture']) && $preference['payment_method_id'] != 'bolbradesco') {
            $preference['capture'] = false;
        }
        // $preference['binary_mode'] = true;

        return [$preference];
    }

}
