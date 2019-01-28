<?php
namespace Itatiaia\Custom\Helper\FeedManager;

class Feed extends \DigitalHub\FeedManager\Helper\Feed
{
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
                $groupedCatalogRuleHelper = $objectManager->get('\Itatiaia\GroupedCatalogRule\Helper\Data');

                // prevenir envio de produtos com valor Zero
                if(!$groupedCatalogRuleHelper->getFinalPrice($product)) continue;

                $item['final_price'] = $groupedCatalogRuleHelper->getFinalPrice($product);
                $item['final_price_formatted'] = $currencyHelper->currency($item['final_price'], true, false);
                // $objectManager->get('\Psr\Log\LoggerInterface')->info('GROUPED FINAL PRICE', [$product->getFinalPrice(), $item['final_price']]);
            } else {
                // prevenir envio de produtos com valor Zero
                if(!$product->getFinalPrice()) continue;

                $item['final_price'] = $product->getFinalPrice();
                $item['final_price_formatted'] = $currencyHelper->currency($item['final_price'], true, false);
            }

            $stockItem = $stockRegistry->getStockItem($product->getId());

            $item['in_stock'] = $stockItem->getIsInStock();
            $item['stock_qty'] = $stockItem->getQty();

            $newCollection[] = $item;
        }

        $variables = [];
        $variables['store_url'] = $urlBuilder->getBaseUrl();
        $variables['store_name'] = $scopeConfig->getValue('general/store_information/name', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $variables['products'] = $newCollection;
        return $variables;
    }
}
