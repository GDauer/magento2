<?php
namespace Itatiaia\SitemapXml\Model\Sitemap\Generator;

class Categories extends \Itatiaia\SitemapXml\Model\Sitemap\Generator
{
    public $_template = '<?xml version="1.0" encoding="UTF-8"?>
        <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
            {{#categories}}
                <url>
                    <loc>{{url}}</loc>
                    <lastmod>{{updated_at}}</lastmod>
                </url>
            {{/categories}}
        </urlset>
    ';

    public function generateXml(\Itatiaia\SitemapXml\Model\Sitemap $sitemap)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $dateFormat = $objectManager->get('\Magento\Framework\Stdlib\DateTime\TimezoneInterface');
        $collection = $this->getCategoryCollection(json_decode($sitemap['conditions'],true));

        $newCollection = [];

        foreach($collection as $_category){
            if($_category->getLevel() < 2) continue;

            $item = $_category->getData();
            $item['url'] = $_category->getUrl();
            $item['updated_at'] = date('d/m/Y H:i:s', strtotime($item['updated_at'] . ' -3 hours'));
            $item['created_at'] = date('d/m/Y H:i:s', strtotime($item['created_at'] . ' -3 hours'));

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
