<?php
namespace DigitalHub\FeedManager\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;

class Feed extends \Magento\Framework\App\Helper\AbstractHelper
{
    private $storeManager;
    private $logger;

    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        LoggerInterface $logger
    ) {
        $this->storeManager = $storeManager;
        $this->logger = $logger;
        parent::__construct($context);
    }

    public function parseMustacheTemplate($code, $variables)
    {
        $m = new \Mustache_Engine();
        return $m->render($code, $variables);
    }

    public function parseTemplateVariables($collection)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $scopeConfig = $objectManager->get('\Magento\Framework\App\Config\ScopeConfigInterface');

        $imagehelper = $objectManager->get('Magento\Catalog\Helper\Image');
        $urlBuilder = $objectManager->get('Magento\Framework\UrlInterface');
        $currencyHelper = $objectManager->get('Magento\Framework\Pricing\Helper\Data');
        $stockRegistry = $objectManager->get('Magento\CatalogInventory\Api\StockRegistryInterface');

        $newCollection = [];

        foreach($collection as $product){
            $item = $product->getData();

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

            $item['final_price'] = $product->getFinalPrice();
            $item['final_price_formatted'] = $currencyHelper->currency($product->getFinalPrice(), true, false);

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

    public function generateFeed($feed, $template)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $directory_list = $objectManager->get('\Magento\Framework\App\Filesystem\DirectoryList');
        $logger = $objectManager->get('\Psr\Log\LoggerInterface');

        // start emulation
        $emulation = $objectManager->get('\Magento\Store\Model\App\Emulation');
        $emulation->startEnvironmentEmulation($feed->getStoreId(), 'frontend', true);

        try {
            $collection = $this->getProductCollection(json_decode($feed->getData('conditions'), true));

            $variables = $this->parseTemplateVariables($collection);

            $xml = $this->parseMustacheTemplate($template->getCode(), $variables);

            $storeCode = null;
            $feedxml_dir = $directory_list->getPath('media') . '/feedxml';

            if (!empty($feed->getStoreId())) {
                $store = $this->storeManager->getStore($feed->getStoreId());
                $storeCode = $store->getCode();
                $feedxml_dir = "/".$storeCode."/".$directory_list->getPath('media') . '/feedxml';
            }

            $file = $feedxml_dir . '/' . $feed->getFilename();

            if(!file_exists($feedxml_dir)){
                mkdir($feedxml_dir);
                chmod($feedxml_dir, 0777);
            }

            file_put_contents($file, $xml);

            $feed->setStatus('generated');
            $feed->setGeneratedAt(date('Y-m-d H:i:s'));
            $feed->save();
        } catch (Exception $e){
            throw new Exception($e->getMessage());
        }

        // end emulation
        $emulation->stopEnvironmentEmulation();
    }

    public function getProductCollection($conditions = [])
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $collection = $objectManager->get('\Magento\Catalog\Model\ProductFactory')->create()->getResourceCollection();
        $collection->addAttributeToSelect('*');
        if($conditions['category']){
            $collection->addCategoriesFilter(array('in' => $conditions['category']));
        }
        $collection->addAttributeToFilter('status', 1);

        if(isset($conditions['only_in_stock']) && (int)$conditions['only_in_stock']){
            $stockFilter = $objectManager->get('\Magento\CatalogInventory\Helper\Stock');
            $stockFilter->addInStockFilterToCollection($collection);
        }

        $productVisibility = $objectManager->get('\Magento\Catalog\Model\Product\Visibility');
        $collection->setVisibility($productVisibility->getVisibleInSiteIds());

        $collection->load();

        return $collection;
    }
}
