<?php


namespace Itatiaia\CustomMercadoPago\Plugin\Model;

use Magento\Backend\Model\UrlInterface;

class CorePlugin
{
    protected $backendUrl;

    public function __construct(
        UrlInterface $backendUrl
    ) {
        $this->backendUrl = $backendUrl;
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
        if (!isset($preference['capture'])) {
            $preference['capture'] = false;
        }

        return [$preference];
    }

}
