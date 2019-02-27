<?php
namespace Itatiaia\SitemapXml\Model\Sitemap\Generator;

class Categories extends \Itatiaia\SitemapXml\Model\Sitemap\Generator
{
    public $_template = '<?xml version="1.0" encoding="UTF-8"?>
        <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
            {{#categories}}
                <url>
                    <loc>{{url}}</loc>
                    <lastmod>{{created_at}}</lastmod>
                </url>
            {{/categories}}
        </urlset>
    ';

    public function generateXml(\Itatiaia\SitemapXml\Model\Sitemap $sitemap)
    {
        $collection = $this->getCategoryCollection(json_decode($sitemap['conditions'],true));

        $newCollection = [];

        foreach($collection as $_category){
            $item = $_category->getData();
            $item['url'] = $_category->getCategoryUrl();

            $newCollection[] = $item;
        }

        $variables = [
            'categories' => $newCollection
        ];

        return $this->parseMustacheTemplate($this->_template, $variables);
    }

    private function getCategoryCollection($conditions = [])
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $collection = $objectManager->get('\Magento\Catalog\Model\CategoryFactory')->create()->getResourceCollection();
        $collection->addAttributeToSelect('*');
        $collection->addAttributeToFilter('is_active', true);
        $collection->load();

        return $collection;
    }
}
