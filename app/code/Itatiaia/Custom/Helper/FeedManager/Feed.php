<?php
namespace Itatiaia\Custom\Helper\FeedManager;

class Feed extends \DigitalHub\FeedManager\Helper\Feed
{
    private $_loadedCategories = [];

    public function parseTemplateVariables($collection)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $scopeConfig = $objectManager->get('\Magento\Framework\App\Config\ScopeConfigInterface');

        $imagehelper = $objectManager->get('Magento\Catalog\Helper\Image');
        $urlBuilder = $objectManager->get('Magento\Framework\UrlInterface');
        $currencyHelper = $objectManager->get('Magento\Framework\Pricing\Helper\Data');
        // $stockItemRepository = $objectManager->get('\Magento\CatalogInventory\Model\Stock\StockItemRepository');
        $stockRegistry = $objectManager->get('Magento\CatalogInventory\Api\StockRegistryInterface');

        $newCollection = [];

        foreach($collection as $product){
            $item = $product->getData();

            // remove sem estoque e com preço zerado por não ter itens vinculados
            if(!$product->isAvailable()) continue;

            foreach($item as $attr_code => $value){
                if($product->getResource()->getAttribute($attr_code)){
                    $item[$attr_code] = $product->getResource()->getAttribute($attr_code)->getFrontend()->getValue($product);
                }
            }

            $item['description'] = strip_tags(html_entity_decode($product->getDescription()));
            $item['short_description'] = strip_tags(html_entity_decode($product->getShortDescription()));
            $item['url'] = $product->getProductUrl();

            $item['image'] = $imagehelper->init($product, 'product_base_image')->getUrl();
            $item['small_image'] = $imagehelper->init($product, 'product_small_image')->getUrl();
            $item['thumbnail'] = $imagehelper->init($product, 'product_thumbnail_image')->getUrl();

            if($product->getTypeId() == 'grouped'){
                $item['is_grouped'] = true;

                $groupedCatalogRuleHelper = $objectManager->get('\Itatiaia\GroupedCatalogRule\Helper\Data');

                // prevenir envio de produtos com valor Zero
                if(!$groupedCatalogRuleHelper->getFinalPrice($product)) continue;

                $item['final_price'] = number_format($groupedCatalogRuleHelper->getFinalPrice($product),2);
                $item['final_price_formatted'] = $currencyHelper->currency($groupedCatalogRuleHelper->getFinalPrice($product), true, false);
                // $objectManager->get('\Psr\Log\LoggerInterface')->info('GROUPED FINAL PRICE', [$product->getFinalPrice(), $item['final_price']]);
            } else {
                // prevenir envio de produtos com valor Zero
                if(!$product->getFinalPrice()) continue;

                $item['final_price'] = number_format($product->getFinalPrice(), 2);
                $item['final_price_formatted'] = $currencyHelper->currency($product->getFinalPrice(), true, false);
            }

            $stockItem = $stockRegistry->getStockItem($product->getId());

            $item['in_stock'] = $stockItem->getIsInStock();
            $item['stock_qty'] = $stockItem->getQty();

            $item['category_path'] = $this->_getCategoryPath($product);
            $item['google_product_category'] = $this->_getGoogleCategoryPath($product);

            $newCollection[] = $item;
        }

        $variables = [];
        $variables['store_url'] = $urlBuilder->getBaseUrl();
        $variables['store_name'] = $scopeConfig->getValue('general/store_information/name', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $variables['products'] = $newCollection;
        return $variables;
    }

    private function _getCategoryPath($product)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $logger = $objectManager->get('\Psr\Log\LoggerInterface');

        $categories = $product->getCategoryIds();
        $last_category_id = array_pop($categories);

        if($last_category_id){
            $category = $this->_getCategory($last_category_id);

            $path = [];
            foreach(explode('/', $category->getPath()) as $cid){
                $c = $this->_getCategory($cid);
                if($c->getLevel() < 2) continue;
                $path[] = $c->getName();
            }

            $path = implode(' > ', $path);

            return $path;
        }
        return '';
    }

    /**
     * @param $product
     * @return string
     */
    private function _getGoogleCategoryPath($product)
    {
        $productCategoryIds = $product->getCategoryIds();
        $lastCategoryId = array_shift($productCategoryIds);

        if (!$lastCategoryId) {
            return '';
        }

        return $this->_getCategory($lastCategoryId)->getData('google_category_path');
    }

    private function _getCategory($category_id)
    {
        if(!isset($this->_loadedCategories[$category_id])){
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $this->_loadedCategories[$category_id] = $objectManager->get('\Magento\Catalog\Model\CategoryFactory')->create()->load($category_id);
        }
        return $this->_loadedCategories[$category_id];
    }
}
