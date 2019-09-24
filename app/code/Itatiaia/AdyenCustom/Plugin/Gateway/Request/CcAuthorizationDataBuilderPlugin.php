<?php

namespace Itatiaia\AdyenCustom\Plugin\Gateway\Request;

use Magento\Payment\Gateway\Request\BuilderInterface;

class CcAuthorizationDataBuilderPlugin
{
    /**
     * This is used to store the paymentResponse data, required on AntiFraud systems
     * @param array $buildSubject
     * @return array|mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function beforeBuild(\Adyen\Payment\Gateway\Request\CcAuthorizationDataBuilder $subject, $buildSubject)
    {
        /** @var \Magento\Payment\Gateway\Data\PaymentDataObject $paymentDataObject */
        $paymentDataObject = \Magento\Payment\Gateway\Helper\SubjectReader::readPayment($buildSubject);
        $payment = $paymentDataObject->getPayment();

        if ($response = $payment->getAdditionalInformation("paymentsResponse")) {
            $payment->setAdditionalInformation("paymentsResponseBkp", json_encode($response));
        }

        return [$buildSubject];
    }
}
