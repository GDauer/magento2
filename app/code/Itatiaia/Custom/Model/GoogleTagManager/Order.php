<?php

namespace Itatiaia\Custom\Model\GoogleTagManager;

class Order extends \MagePal\GoogleTagManager\Model\Order
{
    public function getOrderLayer()
    {
        $collection = $this->getOrderCollection();

        if (!$collection) {
            return false;
        }

        $result = [];

        /* @var \Magento\Sales\Model\Order $order */

        foreach ($collection as $order) {
            $products = [];
            foreach ($order->getAllVisibleItems() as $item) {
                $products[] = [
                    'sku' => $item->getSku(),
                    'id' => $item->getProduct()->getId(),
                    'name' => $this->escapeJsQuote($item->getName()),
                    'price' => $this->gtmHelper->formatPrice($item->getBasePrice()),
                    'quantity' => $item->getQtyOrdered() * 1
                ];
            }

            $transaction =[
                'event' => 'gtm.orderComplete',
                'transactionId' => $order->getIncrementId(),
                'transactionAffiliation' => $this->escapeJsQuote($this->_storeManager->getStore()->getFrontendName()),
                'transactionTotal' => $this->gtmHelper->formatPrice($order->getBaseGrandTotal()),
                'transactionSubTotal' => $this->gtmHelper->formatPrice($order->getBaseSubtotal()),
                'transactionShipping' => $this->gtmHelper->formatPrice($order->getBaseShippingAmount()),
                'transactionTax' => $this->gtmHelper->formatPrice($order->getTaxAmount()),
                'transactionCouponCode' => $order->getCouponCode(),
                'transactionDiscount' => $this->gtmHelper->formatPrice($order->getDiscountAmount()),
                'transactionProducts' => $products
            ];

            $result[] = $transaction;
        }

        return $result;
    }
}
