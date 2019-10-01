<?php

namespace Itatiaia\KondutoCustom\Model\Konduto;

use Konduto\Antifraud\Helper\Data;
use Magento\Quote\Api\CartRepositoryInterface as QuoteRepositoryInterface;
use Konduto\Antifraud\Model\Konduto\PaymentData as OriginalPaymentData;
use Adyen\Payment\Model\NotificationFactory;

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
    public $notificationFactory;

    /**
     * PaymentData constructor.
     * @param Data $helper
     * @param QuoteRepositoryInterface $quoteRepository
     * @param NotificationFactory $notification
     */
    public function __construct(
        Data $helper,
        QuoteRepositoryInterface $quoteRepository,
        NotificationFactory $notification
    ) {
        $this->helper = $helper;
        $this->quoteRepository = $quoteRepository;
        $this->notificationFactory = $notification;
    }

    public function getPaymentData($order)
    {
        $this->order = $order;
        $this->payment = $this->order->getPayment();
        $this->transactions = $this->helper->getTransactions($this->order->getId());

//        if (sizeof($this->transactions) < 2) {
        return $this->getSimplePayment();
//        }
    }

    public function loadQuote($quoteId)
    {
        return $this->quoteRepository->get($quoteId);
    }

    public function getSimplePayment()
    {
        $data = array(
            array(
                "type" => $this->getMethod($this->payment)
            )
        );

        $quote = $this->loadQuote($this->order->getQuoteId());
        if ($this->method === "credit") {
            $this->helper->log('QUOTE: ' . json_encode($quote->getId()), 'info');
            $data[0]['expiration_date'] = $this->getCcExpDate($quote->getPayment());
            $this->helper->log('GETTING CCSTATUS', 'info');
            $data[0]['status'] = $this->getCcStatus($this->order);

            $this->helper->log('GETTING CCLAST4', 'info');
            $last4 = $this->getCcLast4($quote->getPayment());
            if (false !== $last4) {
                $data[0]['last4'] = $last4;
            }

            $this->helper->log('GETTING CCBIN', 'info');
            $bin = $this->getCcBin($quote->getPayment());
            if (false !== $bin) {
                $data[0]['bin'] = $bin;
            }
        } else {
            $this->helper->log('getBoletoExpirationDate ORDER: ' . json_encode($this->order->getId()), 'info');
            $data[0]['expiration_date'] = $this->getBoletoExpirationDate($quote->getPayment());
        }
        $this->helper->log('DATA END: ' . json_encode($data), 'info');

        return $data;
    }

    private function getMethod($payment)
    {
        $this->method = $this->helper->getPaymentType($payment->getMethod());
        if (!$this->method) {
            return false;
        }
        return $this->method;
    }

    private function getCcStatus($order)
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

    private function getCcExpDate($payment)
    {
        $expiryDate = $this->payment->getAdditionalInformation('adyen_expiry_date');
        $this->helper->log('EXPIRY DATE 1: ' . json_encode($expiryDate), 'info');
        if (null === $expiryDate) {
            $pResp = $payment->getAdditionalInformation('paymentsResponse');
            $this->helper->log('PRESP 2: ' . json_encode($pResp), 'info');
            if (isset($pResp['additionalData']) && isset($pResp['additionalData']['adyen_expiry_date'])) {
                $expiryDate = $pResp['additionalData']['adyen_expiry_date']; // mm/yyyy
                $this->helper->log('EXPIRY DATE 2: ' . json_encode($expiryDate), 'info');
            }
        }

        if (null === $expiryDate) {
            $this->helper->log('REAL CCTRANSID: ' . $this->payment->getCcTransId(), 'info');
            $pspRef = $this->payment->getCcTransId();

            $notification = $this->notificationFactory->create()->load($pspRef, 'pspreference');
            $additionalData = unserialize($notification->getAdditionalData());
            $this->helper->log('NOTIFICATION ID: ' . json_encode($notification->getId()), 'info');
            $this->helper->log('PRESP 3: ' . json_encode($additionalData), 'info');
            if (isset($additionalData['expiryDate'])) {
                $expiryDate = $additionalData['expiryDate']; // mm/yyyy
                $this->helper->log('EXPIRY DATE 3: ' . json_encode($expiryDate), 'info');
            }
        }

        return str_replace('/', '', $expiryDate);
    }

    private function getCcBin($payment)
    {
        $pResp = $payment->getAdditionalInformation('paymentsResponse');
        if (isset($pResp['additionalData']) && isset($pResp['additionalData']['cardBin'])) {
            return $pResp['additionalData']['cardBin'];
        }

        $pResp = $this->payment->getAdditionalInformation('paymentsResponseBkp');
        if (isset($pResp['additionalData']) && isset($pResp['additionalData']['cardBin'])) {
            return $pResp['additionalData']['cardBin'];
        }

        $pspRef = $this->payment->getCcTransId();
        $this->helper->log('REAL CCTRANSID: ' . $pspRef, 'info');
        $notification = $this->notificationFactory->create()->load($pspRef, 'pspreference');
        $additionalData = unserialize($notification->getAdditionalData());
        if (isset($additionalData['cardBin'])) {
            return $additionalData['cardBin']; // mm/yyyy
        }

        return false;
    }

    private function getCcLast4($payment)
    {
        if ($this->payment->getCcLast4()) {
            return $this->payment->getCcLast4();
        } else {
            $pResp = $this->payment->getAdditionalInformation('paymentsResponseBkp');
            if (isset($pResp['additionalData']) && isset($pResp['additionalData']['cardSummary'])) {
                return $pResp['additionalData']['cardSummary'];
            }

            $pspRef = $this->payment->getCcTransId();
            $this->helper->log('getCcLast4 REAL CCTRANSID: ' . $pspRef, 'info');
            $notification = $this->notificationFactory->create()->load($pspRef, 'pspreference');
            $additionalData = unserialize($notification->getAdditionalData());
            if (isset($additionalData['cardSummary'])) {
                return $additionalData['cardSummary']; // mm/yyyy
            }
        }

        return false;
    }


    private function getBoletoExpirationDate($payment)
    {
        $pResp = $payment->getAdditionalInformation('paymentsResponse');
        if (isset($pResp['additionalData']) && isset($pResp['additionalData']['cardSummary'])) {
            return $pResp['additionalData']['cardSummary'];
        }

        return false;
    }
}