<?php

namespace Itatiaia\Custom\Model\GoogleTagManager;

class Order extends \MagePal\GoogleTagManager\Model\Order
{
    public function getOrderLayer()
    {
        $collection = $this->getOrderCollection();

        $categoryFactory = \Magento\Framework\App\ObjectManager::getInstance()->get('\Magento\Catalog\Model\CategoryFactory');

        $helper = \Magento\Framework\App\ObjectManager::getInstance()->get('Itatiaia\Custom\Helper\Data');

        if (!$collection) {
            return false;
        }

        $result = [];

        /* @var \Magento\Sales\Model\Order $order */

        foreach ($collection as $order) {
            $products = [];
            foreach ($order->getAllVisibleItems() as $item) {
              $categories = [];

              if($grouped = $helper->getGroupedByOrderItem($item)){
                $groupedPrice = \Magento\Framework\App\ObjectManager::getInstance()->get('Itatiaia\GroupedCatalogRule\Helper\Data')->calculateGroupedFinalPrice($grouped);

                foreach ($grouped->getCategoryIds() as $categoryId) {
                  $categories = $categoryFactory->create()->load($categoryId)->getName();
                }

                $minQty = $helper->getGroupedSuccessQty($grouped, $order);

                $products[] = [
                    'sku' => $grouped->getSku(),
                    'id' => $grouped->getId(),
                    'category' => $categories,
                    'name' => $this->escapeJsQuote($grouped->getName()),
                    'price' => $this->gtmHelper->formatPrice($groupedPrice),
                    'quantity' => $minQty
                ];

              } else {

                foreach ($item->getProduct()->getCategoryIds() as $categoryId) {
                  $categories = $categoryFactory->create()->load($categoryId)->getName();
                }

                $products[] = [
                    'sku' => $item->getSku(),
                    'id' => $item->getProduct()->getId(),
                    'category' => $categories,
                    'name' => $this->escapeJsQuote($item->getName()),
                    'price' => $this->gtmHelper->formatPrice($item->getBasePrice()),
                    'quantity' => $item->getQtyOrdered() * 1
                ];
              }

            }

            $products = $helper->removeDuplicates($products);

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
