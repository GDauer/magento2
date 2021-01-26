<?php

namespace Itatiaia\CustomMercadoPago\Helper;

use Magento\Framework\View\LayoutFactory;


/**
 * Class Data
 *
 * @package MercadoPago\Core\Helper
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Data
    extends \MercadoPago\Core\Helper\Data
{
    public function getAnalyticsData($order)
    {
        $payment = $order->getPayment();
        $additionalInfo = $payment ? $payment->getData('additional_information') : [];
        $analyticsData = [];
        if ($payment && $payment->getData('method')) {
            $methodCode = $payment->getData('method');
            $analyticsData = [
                'payment_id'    => isset($additionalInfo['payment_id_detail']) ? $payment->getData('additional_information')['payment_id_detail'] : '',
                'payment_type'  => 'credit_card',
                'checkout_type' => 'custom'
            ];
            if ($methodCode == \MercadoPago\Core\Model\Custom\Payment::CODE) {
                $analyticsData['public_key'] = $this->scopeConfig->getValue(\MercadoPago\Core\Helper\ConfigData::PATH_PUBLIC_KEY, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            } else {
                $analyticsData['analytics_key'] = $this->getClientIdFromAccessToken($this->scopeConfig->getValue(\MercadoPago\Core\Helper\ConfigData::PATH_ACCESS_TOKEN, \Magento\Store\Model\ScopeInterface::SCOPE_STORE));
                $analyticsData['payment_type'] = 'ticket';
            }
        }
        return $analyticsData;
    }

}