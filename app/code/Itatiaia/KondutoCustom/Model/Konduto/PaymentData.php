<?php

namespace Itatiaia\KondutoCustom\Model\Konduto;

use Konduto\Antifraud\Helper\Data;
use Magento\Quote\Api\CartRepositoryInterface as QuoteRepositoryInterface;
use Konduto\Antifraud\Model\Konduto\PaymentData as OriginalPaymentData;


class PaymentData extends OriginalPaymentData
{
    private $method;
    private $order;
    public $helper;
    public $notification;
    private $transactions;
    private $payment;

    /** @var QuoteRepositoryInterface */
    public $quoteRepository;
//    public $notificationFactory;

    /**
     * PaymentData constructor.
     * @param Data $helper
     * @param QuoteRepositoryInterface $quoteRepository
//     * @param NotificationFactory $notification
     */
    public function __construct(
        Data $helper,
        QuoteRepositoryInterface $quoteRepository
//        NotificationFactory $notification
    ) {
        $this->helper = $helper;
        $this->quoteRepository = $quoteRepository;
//        $this->notificationFactory = $notification;
    }

    /**
     * @param $order
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getPaymentData($order): array
    {
        $this->order = $order;
        $this->payment = $this->order->getPayment();
        $this->transactions = $this->helper->getTransactions($this->order->getId());

        return $this->getSimplePayment();
    }

    /**
     * @param $quoteId
     * @return \Magento\Quote\Api\Data\CartInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function loadQuote($quoteId): \Magento\Quote\Api\Data\CartInterface
    {
        return $this->quoteRepository->get($quoteId);
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getSimplePayment()
    {
        $data = array(
            array(
                "type" => $this->getMethod($this->payment)
            )
        );

        $this->helper->log('METHOD - ' . $this->payment->getMethod(), 'info');
        if (strpos($this->payment->getMethod(), 'mercadopago') !== false) {
            $this->helper->log('ELSEIF - MERCADOPAGO', 'info');
            $data[0] = $this->getMercadoPagoData();
            $data[0]['type'] = $this->getMethod($this->payment);

        } else {
            $this->helper->log('ELSE', 'info');
            $quote = $this->loadQuote($this->order->getQuoteId());
            $this->helper->log('getBoletoExpirationDate ORDER: ' . json_encode($this->order->getId()), 'info');
            $data[0]['expiration_date'] = $this->getBoletoExpirationDate($quote->getPayment());
        }
        $this->helper->log('DATA END: ' . json_encode($data), 'info');

        return $data;
    }

    private function getMethod($payment)
    {
        $this->helper->log('GETMETHOD', 'info', [$payment->getMethod()]);
        if (strpos($payment->getMethod(), 'mercadopago') !== false) {
            return 'credit';
        }

        $this->method = $this->helper->getPaymentType($payment->getMethod());
        if (!$this->method) {
            return false;
        }

        return $this->method;
    }

    /**
     * @return array
     */
    private function getMercadoPagoData(): array
    {
        $data = [];
        $this->helper->log('MERCADOPAGO', 'info');
        $data['expiration_date'] = $this->getCcExpDateMercadoPago();
        $this->helper->log('MERCADOPAGO EXPIRATION DATE', 'info', [$data['expiration_date']]);
        $data['status'] = $this->getCcStatus($this->order);

        $this->helper->log('GETTING CCLAST4 MERCADOPAGO', 'info');
        $last4 = $this->getCcLastMercadoPago();
        if (false !== $last4) {
            $data['last4'] = $last4;
        }

        $this->helper->log('GETTING CCBIN', 'info');
        $bin = $this->getCcBinMercadoPago();
        if (false !== $bin) {
            $data['bin'] = $bin;
        }

        return $data;
    }

    /**
     * @param $quote
     * @return array
     */


    /**
     * @param $order
     * @return string
     */
    private function getCcStatus($order): string
    {
        $paymentSituation = '';
        foreach ($this->transactions as $transaction) {
            $paymentSituation = $transaction['transactionType'];
        }

        if ($paymentSituation === 'capture') {
            return 'approved';
        }

        return 'pending';
    }

    /**
     * @param $payment
     * @return mixed|string
     */


    /**
     * @return string
     */
    private function getCcExpDateMercadoPago()
    {
        $expiryMonth = $this->payment->getAdditionalInformation('card_expiration_month');
        $expiryYear = $this->payment->getAdditionalInformation('card_expiration_year');
        return $expiryMonth . $expiryYear;
    }

    /**
     * @return bool
     */
    private function getCcLastMercadoPago()
    {
        $pResp = $this->payment->getAdditionalInformation('paymentResponse');
        if (isset($pResp['card']) && isset($pResp['card']['last_four_digits'])) {
            return $pResp['card']['last_four_digits'];
        }

        return false;
    }

    /**
     * @return bool
     */
    private function getCcBinMercadoPago()
    {
        $pResp = $this->payment->getAdditionalInformation('paymentResponse');
        if (isset($pResp['card']) && isset($pResp['card']['first_six_digits'])) {
            return $pResp['card']['first_six_digits'];
        }

        return false;
    }

    /**
     * @param $payment
     * @return bool
     */


    /**
     * @param $payment
     * @return bool
     */

    /**
     * @param $payment
     * @return bool
     */
    private function getBoletoExpirationDate($payment)
    {
        $pResp = $payment->getAdditionalInformation('paymentsResponse');
        if (isset($pResp['additionalData']) && isset($pResp['additionalData']['cardSummary'])) {
            return $pResp['additionalData']['cardSummary'];
        }

        return false;
    }
}
