<?php
namespace Itatiaia\AdyenCustom\Gateway\Response;

use Magento\Payment\Gateway\Response\HandlerInterface;

class PaymentAuthorisationDetailsHandler extends \Adyen\Payment\Gateway\Response\PaymentAuthorisationDetailsHandler
{
    /**
     * @param array $handlingSubject
     * @param array $response
     */
    public function handle(array $handlingSubject, array $response)
    {
        $payment = \Magento\Payment\Gateway\Helper\SubjectReader::readPayment($handlingSubject);

        /** @var OrderPaymentInterface $payment */
        $payment = $payment->getPayment();

        // set transaction not to processing by default wait for notification
        $payment->setIsTransactionPending(true);

        // CUSTOM CODE: add flag para envio de e-mail de confirmação
        // $payment->getOrder()->setCanSendNewEmailFlag(false);
        $payment->getOrder()->setCanSendNewEmailFlag(true);

        // set pspReference as transactionId
        $payment->setCcTransId($response['pspReference']);
        $payment->setLastTransId($response['pspReference']);

        // set transaction
        $payment->setTransactionId($response['pspReference']);

        // do not close transaction so you can do a cancel() and void
        $payment->setIsTransactionClosed(false);
        $payment->setShouldCloseParentTransaction(false);
    }
}
