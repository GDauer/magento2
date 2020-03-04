<?php

namespace Itatiaia\Custom\Plugin\CustomerData;

use Magento\Framework\Pricing\Helper\Data as PriceHelper;
use Magento\GroupedProduct\Model\Product\Type\Grouped;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Catalog\Model\ProductFactory;

class Cart
{
    /** @var PriceHelper */
    protected $priceHelper;

    /** @var CheckoutSession */
    protected $checkoutSession;

    /** @var ProductFactory */
    protected $productFactory;

    /** @var array */
    protected $parents = [];

    /**
     * Cart constructor.
     *
     * @param PriceHelper $priceHelper
     * @param CheckoutSession $checkoutSession
     * @param ProductFactory $productFactory
     */
    public function __construct(
        PriceHelper $priceHelper,
        CheckoutSession $checkoutSession,
        ProductFactory $productFactory
    ) {
        $this->priceHelper = $priceHelper;
        $this->checkoutSession = $checkoutSession;
        $this->productFactory = $productFactory;
    }

    /**
     * Plugin afterGetSectionData of Magento Checkout CustomerData Cart
     *
     * @param \Magento\Checkout\CustomerData\Cart $subject
     * @param $result
     * @return mixed
     */
    public function afterGetSectionData(\Magento\Checkout\CustomerData\Cart $subject, $result)
    {
        $currentSummaryCount = $result["summary_count"];

        if (!$currentSummaryCount) {
            return $result;
        }

        $quote = $this->checkoutSession->getQuote();
        $quoteItems = $quote->getAllVisibleItems();
        $this->setParentProducts($quoteItems);

        try {
            $finalItems = [];
            $items = $result["items"];

            foreach ($items as $item) {
                if ($item["product_type"] !== Grouped::TYPE_CODE) {
                    array_push($finalItems, $item);
                    continue;
                }

                $parent = $this->getParent($item["product_id"]);

                if (!$parent) {
                    array_push($finalItems, $item);
                    continue;
                }

                $name = str_replace('/ Volume 1', '', $item["product_name"]);

                if ($parent && !strpos($name, '/ Volume')) {
                    $item["product_name"] = $name;
                    $item["product_price"] = $this->priceHelper->currency($parent["total"], true, true);;
                    array_push($finalItems, $item);
                    continue;
                }

                if ($parent && strpos($name, '/ Volume')) {
                    $currentSummaryCount -= $item["qty"];
                    continue;
                }

                array_push($finalItems, $item);
            }

            $result["items"] = $finalItems;
            $result["summary_count"] = $currentSummaryCount;

            return $result;
        } catch (\Exception $e) {
            return $result;
        }
    }

    /**
     * Get the product parent
     *
     * @param $product_id
     * @return mixed|null
     */
    private function getParent($product_id)
    {
        foreach ($this->parents as $key => $parent) {
            $items = array_map(function ($item) {
                if (isset($item["product_id"])) {
                    return $item["product_id"];
                }
            }, $parent["items"]);

            if (in_array($product_id, $items)) {
                return $parent;
            }
        }
        return null;
    }

    /**
     * Set the parent structure
     *
     * @param $quoteItems
     */
    private function setParentProducts($quoteItems)
    {
        foreach ($quoteItems as $item) {
            $options = $item->getProduct()->getTypeInstance(true)->getOrderOptions($item->getProduct());
            if (!isset($options["super_product_config"]["product_id"])) {
                continue;
            }

            $groupedId = $options["super_product_config"]["product_id"];

            if (isset($this->parents[$groupedId])) {
                $this->addChildren($groupedId, $item);
                continue;
            }

            $parent = $this->productFactory->create()->loadByAttribute("entity_id", $groupedId);
            $this->parents[$parent->getEntityId()] = ["product" => $parent, "total" => 0.0, "items" => []];

            $this->addChildren($groupedId, $item);
        }
    }

    /**
     * Add children to parent structure
     *
     * @param $parentId
     * @param $item
     */
    private function addChildren($parentId, $item)
    {
        array_push($this->parents[$parentId]["items"], $this->getItemData($item));
        $this->parents[$parentId]["total"] += $item->getPrice();
    }

    /**
     * Get the item data
     *
     * @param $item
     * @return array
     */
    private function getItemData($item)
    {
        if (!$item) {
            return [];
        }

        return [
            "id" => $item->getItemId(),
            "product_id" => $item->getProductId(),
            "qty" => $item->getQty(),
            "price" => $item->getPrice()
        ];
    }
}
