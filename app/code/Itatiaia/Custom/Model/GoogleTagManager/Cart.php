<?php

namespace Itatiaia\Custom\Model\GoogleTagManager;

class Cart extends \MagePal\GoogleTagManager\Model\Cart
{
    public function getCart()
    {
        $quote = $this->getQuote();

        $categoryFactory = \Magento\Framework\App\ObjectManager::getInstance()->get('\Magento\Catalog\Model\CategoryFactory');

        $cart = [];

        $cart['hasItems'] = false;

        if ($quote->getItemsCount()) {
            $items = [];
            // set items
            foreach ($quote->getAllVisibleItems() as $item) {
              if(\Magento\Framework\App\ObjectManager::getInstance()->get('Itatiaia\Custom\Helper\Data')->getGroupedByQuoteItem($item)) {
                $grouped = \Magento\Framework\App\ObjectManager::getInstance()->get('Itatiaia\Custom\Helper\Data')->getGroupedByQuoteItem($item);
              }
                $categories = [];

                foreach ($item->getProduct()->getCategoryIds() as $categoryId) {
                  $categories = $categoryFactory->create()->load($categoryId)->getName();
                }

                $items[] = [
                    'sku' => $item->getSku(),
                    'id' => $item->getProduct()->getId(),
                    'category' => $categories,
                    'name' => $this->escapeJsQuote($item->getName()),
                    'price' => $this->gtmHelper->formatPrice($item->getPrice()),
                    'quantity' => $item->getQty(),
                ];
            }

            if(isset($grouped)){
              $groupedPrice = \Magento\Framework\App\ObjectManager::getInstance()->get('Itatiaia\GroupedCatalogRule\Helper\Data')->calculateGroupedFinalPrice($grouped);
              foreach ($grouped->getCategoryIds() as $categoryId) {
                $categories = $categoryFactory->create()->load($categoryId)->getName();
              }

              $items[] = [
                'sku' => $grouped->getSku(),
                'id' => $grouped->getId(),
                'category' => $categories,
                'name' => $this->escapeJsQuote($grouped->getName()),
                'price' => $this->gtmHelper->formatPrice($groupedPrice),
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
