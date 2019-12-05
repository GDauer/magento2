<?php


namespace Itatiaia\CustomMercadoPago\Http\Order;


class Payment
{
    protected $scopeConfig;
    protected $coreHelper;

    public function __construct(
        \MercadoPago\Core\Helper\Data $coreHelper,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->coreHelper = $coreHelper;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param $paymentId
     * @param $data
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function put($paymentId, $data)
    {
        $accessToken = $this->scopeConfig->getValue(\MercadoPago\Core\Helper\ConfigData::PATH_ACCESS_TOKEN, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $this->coreHelper->log("Access Token for Post", 'mercadopago-custom.log', $accessToken);

        //set sdk php mercadopago
        $mp = $this->coreHelper->getApiInstance($accessToken);

        $response = $mp->put("/v1/payments/" . $paymentId, $data);

        return $response;
    }

    private function refundPayment($paymentId)
    {
        $accessToken = $this->scopeConfig->getValue(\MercadoPago\Core\Helper\ConfigData::PATH_ACCESS_TOKEN, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $this->coreHelper->log("Access Token for Refund", 'mercadopago-custom.log', $accessToken);

        //set sdk php mercadopago
        $mp = $this->coreHelper->getApiInstance($accessToken);

        $response = $mp->refund_payment($paymentId);

        return $response;
    }

    /**
     * @param $payment
     * @return array|bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function refund($payment)
    {
        $paymentId = $payment->getRefundTransactionId();

        if (!$paymentId) {
            return false;
        }

        return $this->refundPayment($paymentId);
    }
    /**
     * @param $order
     * @return array|bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function capture($order)
    {
        $paymentResponse = $order->getPayment()->getAdditionalInformation("paymentResponse");
        $paymentId = isset($paymentResponse['id']) ? $paymentResponse['id'] : false;

        if (false === $paymentId) {
            return false;
        }

        return $this->put($paymentId, ['capture' => true]);
    }

    /**
     * @param $order
     * @return array|bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function cancel($order)
    {
        $paymentResponse = $order->getPayment()->getAdditionalInformation("paymentResponse");
        $paymentId = isset($paymentResponse['id']) ? $paymentResponse['id'] : false;

        if (false === $paymentId) {
            return false;
        }

        return $this->put($paymentId, ['status' => 'cancelled']);
    }
}
