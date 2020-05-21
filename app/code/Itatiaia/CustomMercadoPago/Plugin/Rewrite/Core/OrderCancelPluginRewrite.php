<?php


namespace Itatiaia\CustomMercadoPago\Plugin\Rewrite\Core;

use MercadoPago\Core\Plugin\OrderCancelPlugin;

class OrderCancelPluginRewrite extends OrderCancelPlugin
{

    protected function salesOrderBeforeCancel()
    {
        // Does not repeat the return of payment, if it is done through the Mercado Pago
        if ($this->order->getExternalRequest()) {
            return;
        }

        $paymentMethod = $this->order->getPayment()->getMethodInstance()->getCode();
        if (!($paymentMethod == 'mercadopago_custom' || $paymentMethod == 'mercadopago_customticket'|| $paymentMethod == 'mercadopago_custom_bank_transfer' || $paymentMethod == 'mercadopago_basic')) {
            return;
        }

        $cancelAvailable = $this->scopeConfig->getValue(\MercadoPago\Core\Helper\ConfigData::PATH_ORDER_CANCEL_AVAILABLE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        if(!$cancelAvailable){
            $this->dataHelper->log("OrderCancelPlugin::salesOrderBeforeCancel - Cancellation not enabled", 'mercadopago-custom.log');
            return;
        }

        //Get payment info
        $paymentResponse = $this->order->getPayment()->getAdditionalInformation("paymentResponse");

        if(!isset($paymentResponse['id'])){
            $this->throwCancelationException(__("Cancellation can not be executed because the payment id was not found."));
            return;
        }

        $accessToken = $this->scopeConfig->getValue(\MercadoPago\Core\Helper\ConfigData::PATH_ACCESS_TOKEN, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        if(empty($accessToken)){
            $this->throwCancelationException(__("Cancellation can not be performed because ACCESS_TOKEN has not been configured."));
            return;
        }

        //Get Payment Id
        $paymentID = $paymentResponse['id'];

        //Get Sdk Instance
        $mp = $this->dataHelper->getApiInstance($accessToken);

        //Get Payment detail
        $urlGet = "/v1/payments/$paymentID?access_token=$accessToken";
        $response = $mp->get($urlGet);

        if ($response['status'] == 200) {

            if($response['response']['status'] == 'pending' || $response['response']['status'] == 'in_process'){
                $data = ["status" => 'cancelled'];
                $response = $mp->put("/v1/payments/$paymentID?access_token=$accessToken", $data);

                if ($response['status'] == 200) {
                    $this->dataHelper->log("OrderCancelPlugin::salesOrderBeforeCancel - Payment canceled", 'mercadopago-custom.log', $response);
                    $this->messageManager->addSuccessMessage("Mercado Pago - " . __('Payment canceled.'));
                } else {
                    $this->throwCancelationException(__("Could not cancel the payment because of an error returned by the API Mercado Pago."), $response);
                    $this->messageManager->addErrorMessage($response['status'] . ' ' . $response['response']['message']);
                }

            }else{

                if ($response['response']['status'] == 'rejected') {
                    $this->dataHelper->log("OrderCancelPlugin::salesOrderBeforeCancel - Payment was not canceled because the status is rejected.",
                        'mercadopago-custom.log', $response);
                    $this->messageManager->addSuccessMessage("Mercado Pago - " . __('Payment was not canceled because the status is rejected.'));
                } elseif ($response['response']['status'] == 'cancelled') {
                    return;
                } else {
                    $this->throwCancelationException(__("The payment has not been canceled, you can only cancel payments with status pending or in_process. The payment status is ") . $response['response']['status'] . ".");
                }
            }

        }else{
            $this->throwCancelationException(__("An error occurred while getting the status of the payment in the API Mercado Pago."), $response);
        }

        return;
    }
}