<?php
namespace Itatiaia\Custom\Block\Catalog\Product\View;

class Attributes extends \Magento\Catalog\Block\Product\View\Attributes
{
    public function setProduct($product)
    {
        $this->_product = $product;
        return $this;
    }
}
