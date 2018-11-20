<?php
namespace DigitalHub\FixProductTitle\Helper\Product;

use Magento\Framework\View\Result\Page as ResultPage;

class View extends \Magento\Catalog\Helper\Product\View
{
    public function prepareAndRender(ResultPage $resultPage, $productId, $controller, $params = null)
    {
        parent::prepareAndRender($resultPage, $productId, $controller, $params);

        $product = \Magento\Framework\App\ObjectManager::getInstance()->get('\Magento\Catalog\Model\ProductFactory')->create()->load($productId);

        if(!$product->getMetaTitle()){
            $pageConfig = $resultPage->getConfig();
            $pageConfig->getTitle()->set($product->getName());
        }

        return $this;
    }
}
