<?php

namespace Itatiaia\KondutoCustom\Model\Konduto;

use Konduto\Antifraud\Helper\Data;
use Konduto\Antifraud\Model\Konduto\BillingData;
use Konduto\Antifraud\Model\Konduto\CustomerData;
use Konduto\Antifraud\Model\Konduto\ShippingData;
use Konduto\Antifraud\Model\Konduto\ShoppingCartData;
use Konduto\Antifraud\Model\Konduto\PaymentData;
use Konduto\Antifraud\Model\Konduto\OrderData as OriginalOrderData;

/**
 * Class OrderData
 * @package Konduto\Antifraud\Model\Konduto
 */
class OrderData extends OriginalOrderData
{
    /**
     * @var PaymentData
     */
    private $paymentData;
    /**
     * @var CustomerData
     */
    private $customerData;
    /**
     * @var BillingData
     */
    private $billingData;
    /**
     * @var ShippingData
     */
    private $shippingData;
    /**
     * @var ShoppingCartData
     */
    private $shoppingCartData;

    /**
     * OrderData constructor.
     * @param PaymentData $paymentData
     * @param \Konduto\Antifraud\Model\Konduto\CustomerData $customerData
     * @param \Konduto\Antifraud\Model\Konduto\BillingData $billingData
     * @param \Konduto\Antifraud\Model\Konduto\ShippingData $shippingData
     * @param \Konduto\Antifraud\Model\Konduto\ShoppingCartData $shoppingCartData
     * @param Data $helper
     */
    public function __construct(
        PaymentData $paymentData,
        CustomerData $customerData,
        BillingData $billingData,
        ShippingData $shippingData,
        ShoppingCartData $shoppingCartData,
        Data $helper
    ){
        $this->paymentData = $paymentData;
        $this->customerData = $customerData;
        $this->billingData = $billingData;
        $this->shippingData = $shippingData;
        $this->shoppingCartData = $shoppingCartData;

        parent::__construct(
            $paymentData,
            $customerData,
            $billingData,
            $shippingData,
            $shoppingCartData,
            $helper
        );
    }

    /**
     * @param $order
     * @return object
     * @throws \Exception
     */
    public function getOrderData($order)
    {
        $orderKonduto = new \Konduto\Models\Order;
        $orderKonduto->setId($order->getIncrementId());
        if ($order->getVisitorId()) {
            $orderKonduto->setVisitor($order->getVisitorId());
        }
        $orderKonduto->setTotalAmount((float) $this->treatCents($order->getGrandTotal()));
        $orderKonduto->setShippingAmount((float) $this->treatCents($order->getShippingAmount()));
        $orderKonduto->setCurrency($order->getBaseCurrencyCode());
        $orderKonduto->setIp($order->getRemoteIp());
        $orderKonduto->setCustomer($this->customerData->getCustomerData($order));
        $a = $this->paymentData->getPaymentData($order);
        $orderKonduto->setPayment($a);
        $this->helper->log('PAYMENT_DATA : ' . json_encode($a), 'info');
        $orderKonduto->setBilling($this->billingData->getBillingData($order->getBillingAddress()));
        if ($order->getShippingAddress()) {
            $orderKonduto->setShipping($this->shippingData->getShippingData($order->getShippingAddress()));
        }
        $orderKonduto->setShoppingCart($this->shoppingCartData->getItems($order));

        $this->helper->log('ORDER _DATA : ' . json_encode((array) $orderKonduto), 'info');
        $this->helper->log('ORDER P_DATA : ' . json_encode($orderKonduto->toJsonArray()), 'info');
        return $orderKonduto;
    }

    public function treatCents($number)
    {
        return number_format($number, 2, '.', '');
    }
}