<?php
/**
 * Copyright ©  All rights reserved.
 */
declare(strict_types=1);

namespace DigitalHub\GroupedProduct\Rewrite\Magento\Quote\Model\Quote;

class Item extends \Magento\Quote\Model\Quote\Item
{
	/**
     * Check product representation in item
     *
     * @param   \Magento\Catalog\Model\Product $product
     * @return  bool
     */
    public function representProduct($product)
    {
        $parentResult = parent::representProduct($product);

        if ($parentResult === false) {
            return $parentResult;
        }

        $itemOptions = $this->getOptionsByCode();
        $productOptions = $product->getCustomOptions();
        
        foreach ($itemOptions as $option) {
            $code = $option->getCode();
            if ($code == 'product_type' && $productOptions[$code]->getValue() === 'grouped' && $productOptions[$code]->getValue() === $option->getValue()) {
                return false;
            }
        }

        return true;
    }
}

