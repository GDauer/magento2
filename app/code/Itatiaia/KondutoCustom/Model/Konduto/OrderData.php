<?php

namespace Itatiaia\KondutoCustom\Model\Konduto;

use Konduto\Antifraud\Helper\Data;
use Konduto\Core\Konduto;
use Konduto\Models\Order;

/**
 * Class OrderData
 * @package Konduto\Antifraud\Model\Konduto
 */
class OrderData extends \Konduto\Antifraud\Model\Konduto\AbstractData
{
    /**
     * @var
     */
    private $orderData;
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
        \Konduto\Antifraud\Model\Konduto\CustomerData $customerData,
        \Konduto\Antifraud\Model\Konduto\BillingData $billingData,
        \Konduto\Antifraud\Model\Konduto\ShippingData $shippingData,
        \Konduto\Antifraud\Model\Konduto\ShoppingCartData $shoppingCartData,
        Data $helper
    ){
        $this->paymentData = $paymentData;
        $this->customerData = $customerData;
        $this->billingData = $billingData;
        $this->shippingData = $shippingData;
        $this->shoppingCartData = $shoppingCartData;
        parent::__construct($helper);
    }

    /**
     * @param $order
     * @return object
     * @throws \Exception
     */
    public function getOrderData($order)
    {
        $orderKonduto = new Order;
        $orderKonduto->setId($order->getIncrementId());
        if ($order->getVisitorId()) {
            $orderKonduto->setVisitor($order->getVisitorId());
        }
        $orderKonduto->setTotalAmount((float) $this->treatCents($order->getGrandTotal()));
        $orderKonduto->setShippingAmount((float) $this->treatCents($order->getShippingAmount()));
        $orderKonduto->setCurrency($order->getBaseCurrencyCode());
        $orderKonduto->setIp($order->getRemoteIp());
        $orderKonduto->setCustomer($this->customerData->getCustomerData($order));
        $orderKonduto->setPayment($this->paymentData->getPaymentData($order));
        $this->helper->log('PAYMENT_DATA : ' . json_encode($this->paymentData->getPaymentData($order)), 'info');
        $orderKonduto->setBilling($this->billingData->getBillingData($order->getBillingAddress()));
        if ($order->getShippingAddress()) {
            $orderKonduto->setShipping($this->shippingData->getShippingData($order->getShippingAddress()));
        }
        $orderKonduto->setShoppingCart($this->shoppingCartData->getItems($order));

        return (object) $orderKonduto;
    }

    public function treatCents($number)
    {
        return number_format($number, 2, '.', '');
    }
}