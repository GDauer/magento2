<?php
namespace Itatiaia\SitemapXml\Model\Sitemap\Generator;

class Products extends \Itatiaia\SitemapXml\Model\Sitemap\Generator
{
    public $_template = '<?xml version="1.0" encoding="UTF-8"?>
        <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
            {{#products}}
                <url>
                    <loc>{{url}}</loc>
                    <lastmod>{{created_at}}</lastmod>
                </url>
            {{/products}}
        </urlset>
    ';

    public function generateXml(\Itatiaia\SitemapXml\Model\Sitemap $sitemap)
    {
        $collection = $this->getProductCollection(json_decode($sitemap['conditions'],true));

        $newCollection = [];

        foreach($collection as $_product){
            $item = $_product->getData();
            $item['url'] = $_product->getProductUrl();

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
        $collection->addAttributeToSelect('*');
        if(isset($conditions['category'])){
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
