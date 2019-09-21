<?php

namespace Itatiaia\KondutoCustom\Model\Konduto;

use Konduto\Antifraud\Helper\Data;
use Magento\Quote\Api\CartRepositoryInterface as QuoteRepositoryInterface;
use Konduto\Antifraud\Model\Konduto\PaymentData as OriginalPaymentData;

class OrderData extends OriginalPaymentData
{
    private $method;
    private $order;
    public $helper;
    private $transactions;
    private $payment;

    /** @var QuoteRepositoryInterface  */
    public $quoteRepository;

    /**
     * PaymentData constructor.
     * @param Data $helper
     * @param QuoteRepositoryInterface $quoteRepository
     */
    public function __construct(Data $helper, QuoteRepositoryInterface $quoteRepository)
    {
        $this->helper = $helper;
        $this->quoteRepository = $quoteRepository;
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

        if ($this->method === "credit") {
            $quote = $this->loadQuote($this->order->getQuoteId());
            $this->helper->log('QUOTE: ' . json_encode($quote->getId()), 'info');
            $data[0]['expiration_date'] = $this->getCcExpDate($quote->getPayment());
            $data[0]['status'] = $this->getCcStatus($this->order);

            $last4 = $this->getCcLast4($quote->getPayment());;
            if (false !== $last4) {
                $data[0]['last4'] = $last4;
            }
            $bin = $this->getCcBin($quote->getPayment());
            if (false !== $bin) {
                $data[0]['bin'] = $bin;
            }
        } else {
            $this->helper->log('ORDER: ' . json_encode($this->order->getId()), 'info');
            $data[0]['expiration_date'] = $this->getBoletoExpirationDate($this->order->getPayment());
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
        $pResp = $payment->getAdditionalInformation('paymentsResponse');
        $expiryDate = $pResp['additionalData']['expiryDate']; // mm/yyyy
        return str_replace('/', '', $expiryDate);
    }

    private function getCcBin($payment)
    {
        $pResp = $payment->getAdditionalInformation('paymentsResponse');
        $this->helper->log('QUOTE PAYMENTRESPONSE: ' . json_encode($pResp), 'info');
        $this->helper->log('QUOTE cardBin: ' . json_encode($pResp['additionalData']['cardBin']), 'info');
        if (isset($pResp['additionalData']) && isset($pResp['additionalData']['cardBin'])) {
            return $pResp['additionalData']['cardBin'];
        }

        return false;
    }

    private function getCcLast4($payment)
    {
        $pResp = $payment->getAdditionalInformation('paymentsResponse');
        if (isset($pResp['additionalData']) && isset($pResp['additionalData']['cardSummary'])) {
            return $pResp['additionalData']['cardSummary'];
        }

        return false;
    }

    private function getBoletoExpirationDate($payment)
    {
        if ($this->payment->getCcLast4()) {
            return $this->payment->getCcLast4();
        } else {
            $pResp = $payment->getAdditionalInformation('paymentsResponse');
            if (isset($pResp['additionalData']) && isset($pResp['additionalData']['cardSummary'])) {
                return $pResp['additionalData']['cardSummary'];
            }
        }
        return false;
    }
}