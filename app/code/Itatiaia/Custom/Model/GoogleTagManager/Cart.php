<?php

namespace Itatiaia\Custom\Model\GoogleTagManager;

class Cart extends \MagePal\GoogleTagManager\Model\Cart
{
    public function getCart()
    {
        $quote = $this->getQuote();

        $cart = [];

        $cart['hasItems'] = false;

        if ($quote->getItemsCount()) {
            $items = [];
            // set items
            foreach ($quote->getAllVisibleItems() as $item) {
                $items[] = [
                    'sku' => $item->getSku(),
                    'id' => $item->getId(),
                    'name' => $this->escapeJsQuote($item->getName()),
                    'price' => $this->gtmHelper->formatPrice($item->getPrice()),
                    'quantity' => $item->getQty(),
                ];
            }

            if (count($items) > 0) {
                $cart['hasItems'] = true;
                $cart['items'] = $items;
            }

            $cart['total'] = $this->gtmHelper->formatPrice($quote->getGrandTotal());
            $cart['itemCount'] = $quote->getItemsCount() * 1;
            $cart['cartQty'] = $quote->getItemsQty() * 1;

            //set coupon code
            $coupon = $quote->getCouponCode();

            $cart['hasCoupons'] = $coupon ? true : false;

            if ($coupon) {
                $cart['couponCode'] = $coupon;
            }
        }

        return $cart;
    }
}
