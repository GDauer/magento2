<?php
namespace Itatiaia\Custom\Helper\FeedManager;

use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Catalog\Model\CategoryFactory;
use Psr\Log\LoggerInterface;
use Itatiaia\GroupedCatalogRule\Helper\Data as groupedCatalogRuleHelper;
use Magento\Catalog\Helper\Image;
use Magento\Framework\UrlInterface;
use Magento\Framework\Pricing\Helper\Data as currencyHelper;
use Magento\CatalogInventory\Api\StockRegistryInterface;

class Feed extends \DigitalHub\FeedManager\Helper\Feed
{
    private $_loadedCategories = [];

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var CategoryFactory
     */
    private $categoryFactory;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfigInterface;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var groupedCatalogRuleHelper
     */
    private $groupedCatalogRuleHelper;

    /**
     * @var Image
     */
    private $imageHelper;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var currencyHelper
     */
    private $currencyHelper;

    /**
     * @var StockRegistryInterface
     */
    private $stockRegistry;

    public function __construct(
        StoreManagerInterface $storeManager,
        CategoryFactory $categoryFactory,
        ScopeConfigInterface $scopeConfigInterface,
        LoggerInterface $logger,
        groupedCatalogRuleHelper $groupedCatalogRuleHelper,
        Image $imageHelper,
        UrlInterface $urlBuilder,
        currencyHelper $currencyHelper,
        StockRegistryInterface $stockRegistry
    )
    {
        $this->storeManager = $storeManager;
        $this->categoryFactory = $categoryFactory;
        $this->scopeConfigInterface = $scopeConfigInterface;
        $this->logger = $logger;
        $this->groupedCatalogRuleHelper = $groupedCatalogRuleHelper;
        $this->imageHelper = $imageHelper;
        $this->urlBuilder = $urlBuilder;
        $this->currencyHelper = $currencyHelper;
        $this->stockRegistry = $stockRegistry;
    }

    public function parseTemplateVariables($collection)
    {
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
            $item['url_code'] = str_replace($this->urlBuilder->getBaseUrl(), $this->storeManager->getStore()->getBaseUrl(), $product->getProductUrl());

            $item['image'] = $this->imageHelper->init($product, 'product_base_image')->getUrl();
            $item['small_image'] = $this->imageHelper->init($product, 'product_small_image')->getUrl();
            $item['thumbnail'] = $this->imageHelper->init($product, 'product_thumbnail_image')->getUrl();

            if($product->getTypeId() == 'grouped'){
                $finalPrice = $this->groupedCatalogRuleHelper->getFinalPrice($product);
            } else {
                $finalPrice = $product->getFinalPrice();
            }

            if(!$finalPrice) continue;

            $this->setProductDataPrice($product, $item, $finalPrice);

            $stockItem = $this->stockRegistry->getStockItem($product->getId());

            $item['in_stock'] = $stockItem->getIsInStock();
            $item['stock_qty'] = $stockItem->getQty();

            $item['category_path'] = $this->_getCategoryPath($product);
            $item['google_product_category'] = $this->_getGoogleCategoryPath($product);

            $newCollection[] = $item;
        }

        $variables = [];
        $variables['store_url'] = $this->urlBuilder->getBaseUrl();
        $variables['store_url_code'] = $this->storeManager->getStore()->getBaseUrl();
        $variables['store_name'] = $this->scopeConfigInterface->getValue('general/store_information/name', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $variables['products'] = $newCollection;
        return $variables;
    }

    private function _getCategoryPath($product)
    {
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
        if(!isset($this->_loadedCategories[$category_id]))
        {
            $this->_loadedCategories[$category_id] = $this->categoryFactory->create()->load($category_id);
        }
        return $this->_loadedCategories[$category_id];
    }

    /**
     * Add prices in product array.
     *
     * @param array $productData Product data.
     */
    protected function setProductDataPrice(\Magento\Catalog\Model\Product  $product, &$productData, $finalPrice)
    {
        $productData['is_promotional'] = 0;
        if($product->getTypeId() == 'grouped'){
            $productData['is_grouped'] = true;

            $regularPrice = $this->groupedCatalogRuleHelper->getRegularPrice($product);

            if($finalPrice > 0 && $regularPrice > 0 && $regularPrice > $finalPrice)
            {
                $productData['is_promotional'] = 1;
                $productData['final_price'] = number_format($regularPrice,2);;
                $productData['final_price_formatted'] = $this->currencyHelper->currency($regularPrice, true, false);
                $productData['special_price'] = number_format($finalPrice,2);
                $productData['special_price_formatted'] = $this->currencyHelper->currency($finalPrice, true, false);
            } else {
                $productData['final_price'] = number_format($finalPrice,2);
                $productData['final_price_formatted'] = $this->currencyHelper->currency($finalPrice, true, false);
            }
        } else {
            $productData['final_price'] = number_format($product->getFinalPrice(), 2);
            $productData['final_price_formatted'] = $this->currencyHelper->currency($product->getFinalPrice(), true, false);

            $regularPrice = (float) $product->getPriceInfo()->getPrice(\Magento\Catalog\Pricing\Price\RegularPrice::PRICE_CODE)->getValue();
            $finalPriceAmt = (float) $product->getPriceInfo()->getPrice(\Magento\Catalog\Pricing\Price\FinalPrice::PRICE_CODE)->getValue();

            if($finalPriceAmt > 0 && $regularPrice > 0 && $regularPrice > $finalPriceAmt)
            {
                $productData['is_promotional'] = 1;
                $productData['special_price'] = number_format($finalPriceAmt, 2);
                $productData['special_price_formatted'] = $this->currencyHelper->currency($finalPriceAmt, true, false);
            }
        }

        if ($product->hasData('special_price'))
        {
            $productData['is_promotional'] = 1;
            $productData['final_price'] = number_format($product->getPrice(), 2);
            $productData['final_price_formatted'] = $this->currencyHelper->currency($product->getPrice(), true, false);
            $productData['special_price'] = $product->getSpecialPrice();
            $productData['special_price_formatted'] = $this->currencyHelper->currency($product->getSpecialPrice(), true, false);
        }
    }
}
