<?php

/**
 * PHP version 7
 *
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2020 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 * @link        http://www.webjump.com.br
 */

declare(strict_types=1);

namespace Itatiaia\CustomMercadoPago\Model\Custom;

class Payment extends \MercadoPago\Core\Model\Custom\Payment
{
    /**
     * @param DataObject $data
     * @return $this|\Magento\Payment\Model\Method\Cc
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function assignData(\Magento\Framework\DataObject $data)
    {
        if (!($data instanceof \Magento\Framework\DataObject)) {
            $data = new \Magento\Framework\DataObject($data);
        }

        $infoForm = $data->getData();

        if (isset($infoForm['additional_data'])) {
            if (empty($infoForm['additional_data'])) {
                return $this;
            }
            $additionalData = $infoForm['additional_data'];

            if (isset($additionalData['one_click_pay']) && $additionalData['one_click_pay'] == 1) {
                $additionalData = $this->cleanFieldsOcp($additionalData);
            }

            if (empty($additionalData['token'])) {
//                $this->_helperData->log("CustomPayment::assignData - Token for payment creation was not generated, therefore it is not possible to continue the transaction");
                throw new \Magento\Framework\Exception\LocalizedException(__(\MercadoPago\Core\Helper\Response::PAYMENT_CREATION_ERRORS['TOKEN_EMPTY']));
            }

            $info = $this->getInfoInstance();

            $info->setAdditionalInformation($additionalData);
            $info->setAdditionalInformation('method', $infoForm['method']);
            $info->setAdditionalInformation('payment_type_id', "credit_card");
            $info->setAdditionalInformation('payment_method', $additionalData['payment_method_id']);
            $info->setAdditionalInformation('cardholderName', $additionalData['card_holder_name']);

            if (!empty($additionalData['card_expiration_month']) && !empty($additionalData['card_expiration_year'])) {
                $info->setAdditionalInformation('expiration_date', $additionalData['card_expiration_month'] . "/" . $additionalData['card_expiration_year']);
            }

            if (isset($additionalData['gateway_mode'])) {
                $info->setAdditionalInformation('gateway_mode', $additionalData['gateway_mode']);
            }

            if (!empty($additionalData['coupon_code'])) {
                $info->setAdditionalInformation('coupon_code', $additionalData['coupon_code']);
            }
        }

        return $this;
    }

    /**
     * @param string $paymentAction
     * @param object $stateObject
     * @return $this|bool|\Magento\Payment\Model\Method\Cc
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \MercadoPago\Core\Model\Api\V1\Exception
     */
    public function initialize($paymentAction, $stateObject)
    {
        if ($this->getInfoInstance()->getAdditionalInformation('token') == "") {
//            $this->_helperData->log("CustomPayment::initialize - Token for payment creation was not generated, therefore it is not possible to continue the transaction");
            throw new \Magento\Framework\Exception\LocalizedException(__(\MercadoPago\Core\Helper\Response::PAYMENT_CREATION_ERRORS['TOKEN_EMPTY']));
        }

        $preference = $this->createCustomPreference();
        return $this->createCustomPayment($preference);
    }

    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function createCustomPreference()
    {
        try {
            $order = $this->getInfoInstance()->getOrder();
            $payment = $order->getPayment();
            $paymentInfo = $this->getPaymentInfo($payment);

            $preference = $this->_coreModel->makeDefaultPreferencePaymentV1($paymentInfo, $this->_getQuote(), $order);
            $preference['installments'] = (int)$payment->getAdditionalInformation("installments");
            $paymentMethod = $payment->getAdditionalInformation("payment_method_id");
            if ($paymentMethod == "") {
                $paymentMethod = $payment->getAdditionalInformation("payment_method_selector");
            }

            $preference['payment_method_id'] = $paymentMethod;
            $preference['token'] = $payment->getAdditionalInformation("token");
            $preference['metadata']['token'] = $payment->getAdditionalInformation("token");

            if ($payment->getAdditionalInformation("issuer_id") != "" && $payment->getAdditionalInformation("issuer_id") > -1) {
                $preference['issuer_id'] = (int)$payment->getAdditionalInformation("issuer_id");
            }

            if ($payment->getAdditionalInformation("gateway_mode")) {
                $preference['processing_mode'] = 'gateway';
            }

            $preference['binary_mode'] = $this->_scopeConfig->isSetFlag(\MercadoPago\Core\Helper\ConfigData::PATH_CUSTOM_BINARY_MODE);
            $preference['statement_descriptor'] = $this->_scopeConfig->getValue(\MercadoPago\Core\Helper\ConfigData::PATH_CUSTOM_STATEMENT_DESCRIPTOR);

//            $this->_helperData->log("CustomPayment::initialize - Credit Card: Preference to POST /v1/payments", self::LOG_NAME, $preference);
            return $preference;
        } catch (\Exception $e) {
//            $this->_helperData->log("CustomPayment::initialize - There was an error retrieving the information to create the payment, more details: " . $e->getMessage());
            throw new \Magento\Framework\Exception\LocalizedException(__(\MercadoPago\Core\Helper\Response::PAYMENT_CREATION_ERRORS['INTERNAL_ERROR_MODULE']));
        }
    }

    /**
     * @param $preference
     * @return $this|bool
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \MercadoPago\Core\Model\Api\V1\Exception
     */
    public function createCustomPayment($preference)
    {
        $response = $this->_coreModel->postPaymentV1($preference);
        if (isset($response['status']) && ($response['status'] == 200 || $response['status'] == 201)) {
            $this->getInfoInstance()->setAdditionalInformation("paymentResponse", $response['response']);
            return true;
        }
        $messageErrorToClient = $this->_coreModel->getMessageError($response);
        $arrayLog = array(
            "response" => $response,
            "message" => $messageErrorToClient
        );
//        $this->_helperData->log("CustomPayment::initialize - The API returned an error while creating the payment, more details: " . json_encode($arrayLog));
        throw new \Magento\Framework\Exception\LocalizedException(__($messageErrorToClient));
    }

    /**
     * @param \Magento\Quote\Api\Data\CartInterface|null $quote
     * @return bool
     */
    public function isAvailable(\Magento\Quote\Api\Data\CartInterface $quote = null)
    {
        $isActive = $this->_scopeConfig->getValue(\MercadoPago\Core\Helper\ConfigData::PATH_CUSTOM_ACTIVE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if (empty($isActive)) {
            return false;
        }

        $secure = $this->_request->isSecure();
        if ($secure === FALSE) {
//            $this->_helperData->log("CustomPayment::isAvailable - Module not available because it has production credentials in non HTTPS environment.");
            return false;
        }

        return $this->available($quote);
    }

    /**
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function available(\Magento\Quote\Api\Data\CartInterface $quote = null)
    {
        $parent = parent::isAvailable($quote);
        $status = true;

        if (!$parent) {
//            $this->_helperData->log("CustomPayment::isAvailable - Module not available due to magento rules.");
            $status = false;
        }

        $accessToken = $this->_scopeConfig->getValue(\MercadoPago\Core\Helper\ConfigData::PATH_ACCESS_TOKEN, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if (empty($accessToken)) {
//            $this->_helperData->log("CustomPayment::isAvailable - Module not available because access_token has not been configured.");
            return false;
        }

        $public_key = $this->_scopeConfig->getValue(\MercadoPago\Core\Helper\ConfigData::PATH_PUBLIC_KEY, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if (empty($public_key)) {
//            $this->_helperData->log("CustomPayment::isAvailable - Module not available because public_key has not been configured.");
            return false;
        }

        if (!$this->_helperData->isValidAccessToken($accessToken)) {
//            $this->_helperData->log("CustomPayment::isAvailable - Module not available because access_token is not valid.");
            return false;
        }

        return $status;
    }

    /**
     * Saves customer tokenized card to be used later by OCP
     *
     * @param $customer
     * @param $token
     * @param $payment
     *
     * @return array|bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function checkAndcreateCard($customer, $token, $payment)
    {

        $accessToken = $this->_scopeConfig->getValue(\MercadoPago\Core\Helper\ConfigData::PATH_ACCESS_TOKEN, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $mp = $this->_helperData->getApiInstance($accessToken);

        foreach ($customer['cards'] as $card) {


            if ($card['first_six_digits'] == $payment['card']['first_six_digits']
                && $card['last_four_digits'] == $payment['card']['last_four_digits']
                && $card['expiration_month'] == $payment['card']['expiration_month']
                && $card['expiration_year'] == $payment['card']['expiration_year']
            ) {
//                $this->_helperData->log("Card already exists", self::LOG_NAME, $card);

                return $card;
            }
        }
        $params = ["token" => $token];
        if (isset($payment['issuer_id'])) {
            $params['issuer_id'] = (int)$payment['issuer_id'];
        }
        if (isset($payment['payment_method_id'])) {
            $params['payment_method_id'] = $payment['payment_method_id'];
        }
        $card = $mp->post("/v1/customers/" . $customer['id'] . "/cards", $params);

//        $this->_helperData->log("Response create card", self::LOG_NAME, $card);

        if ($card['status'] == 201) {
            return $card['response'];
        }

        return false;
    }

    /**
     * Saves to be used later by OCP
     *
     * @param $email
     *
     * @return bool|array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getOrCreateCustomer($email)
    {
        if (empty($email)) {
            return false;
        }
        //get access_token
        if (!$this->_accessToken) {
            $this->_accessToken = $this->_scopeConfig->getValue(\MercadoPago\Core\Helper\ConfigData::PATH_ACCESS_TOKEN, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        }

        $mp = $this->_helperData->getApiInstance($this->_accessToken);

        $customer = $mp->get("/v1/customers/search", ["email" => $email]);

//        $this->_helperData->log("Response search customer", self::LOG_NAME, $customer);

        if ($customer['status'] == 200) {

            if ($customer['response']['paging']['total'] > 0) {
                return $customer['response']['results'][0];
            } else {
//                $this->_helperData->log("Customer not found: " . $email, self::LOG_NAME);

                $customer = $mp->post("/v1/customers", ["email" => $email]);

//                $this->_helperData->log("Response create customer", self::LOG_NAME, $customer);

                if ($customer['status'] == 201) {
                    return $customer['response'];
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }
}
