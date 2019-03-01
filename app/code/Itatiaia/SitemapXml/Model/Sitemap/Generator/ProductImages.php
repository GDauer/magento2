<?php
namespace Itatiaia\SitemapXml\Model\Sitemap\Generator;

class ProductImages extends \Itatiaia\SitemapXml\Model\Sitemap\Generator
{
    public $_template = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
  {{#products}}
  <url>
    <loc>{{url}}</loc>
    {{#images}}
    <image:image>
      <image:loc>{{url}}</image:loc>
    </image:image>
    {{/images}}
  </url>
  {{/products}}
</urlset>
    ';

    public function generateXml(\Itatiaia\SitemapXml\Model\Sitemap $sitemap)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $dateFormat = $objectManager->get('\Magento\Framework\Stdlib\DateTime\TimezoneInterface');
        $imageHelper = $objectManager->get('Magento\Catalog\Helper\Image');
        $collection = $this->getProductCollection(json_decode($sitemap['conditions'],true));

        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $mediaUrl = $storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

        $newCollection = [];

        foreach($collection as $p){
            $_product = $objectManager->get('\Magento\Catalog\Model\ProductFactory')->create()->load($p->getId());

            $item = $_product->getData();
            $item['url'] = $_product->getProductUrl();
            $item['updated_at'] = $dateFormat->date($item['updated_at'])->format('d/m/Y H:i:s');
            $item['created_at'] = $dateFormat->date($item['created_at'])->format('d/m/Y H:i:s');

            $item['images'] = [];
            foreach ($_product->getMediaGalleryImages() as $image) {
                $item['images'][] = [
                    'url' => $mediaUrl . 'catalog/product' . $image->getFile()
                ];
            }

            $newCollection[] = $item;
        }

        $variables = [
            'products' => $newCollection
        ];

        return $this->parseMustacheTemplate($this->_template, $variables);
    }

    private function getProductCollection($conditions = [])
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $collection = $objectManager->get('\Magento\Catalog\Model\ProductFactory')->create()->getResourceCollection();
        $collection->addAttributeToSelect(['status','category_ids','visibility']);

        if(isset($conditions['category']) && $conditions['category']){
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
