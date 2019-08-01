<?php

namespace Itatiaia\AdyenCustom\Gateway\Response;

class CheckoutPaymentsDetailsHandler extends \Adyen\Payment\Gateway\Response\CheckoutPaymentsDetailsHandler
{
    public function handle(array $handlingSubject, array $response)
    {
        $paymentDataObject = \Magento\Payment\Gateway\Helper\SubjectReader::readPayment($handlingSubject);

        $payment = $paymentDataObject->getPayment();

        // set transaction not to processing by default wait for notification
        $payment->setIsTransactionPending(true);

        // no not send order confirmation mail
//        $payment->getOrder()->setCanSendNewEmailFlag(false);

        // CUSTOM TO SEND EMAIL
         $payment->getOrder()->setCanSendNewEmailFlag(true);

        if (!empty($response['pspReference'])) {
            // set pspReference as transactionId
            $payment->setCcTransId($response['pspReference']);
            $payment->setLastTransId($response['pspReference']);

            // set transaction
            $payment->setTransactionId($response['pspReference']);
        }

        if (!empty($response['additionalData']['recurring.recurringDetailReference']) &&
            !$this->adyenHelper->isCreditCardVaultEnabled() &&
            $payment->getMethodInstance()->getCode() !== \Adyen\Payment\Model\Ui\AdyenOneclickConfigProvider::CODE
        ) {
            $order = $payment->getOrder();
            $this->adyenHelper->createAdyenBillingAgreement($order, $response['additionalData']);
        }

        // do not close transaction so you can do a cancel() and void
        $payment->setIsTransactionClosed(false);
        $payment->setShouldCloseParentTransaction(false);
    }
}