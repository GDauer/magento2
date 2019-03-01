<?php
namespace Itatiaia\SitemapXml\Model\Sitemap\Generator;

class Index extends \Itatiaia\SitemapXml\Model\Sitemap\Generator
{
    public $_template = '<?xml version="1.0" encoding="UTF-8"?>
        <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
            {{#sitemaps}}
                <url>
                    <loc>{{url}}</loc>
                    <lastmod>{{updated_at}}</lastmod>
                </url>
            {{/sitemaps}}
        </urlset>
    ';

    public function generateXml(\Itatiaia\SitemapXml\Model\Sitemap $sitemap)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $dateFormat = $objectManager->get('\Magento\Framework\Stdlib\DateTime\TimezoneInterface');
        $urlBuilder = $objectManager->get('Magento\Framework\UrlInterface');

        $collection = $this->getSitemapCollection(json_decode($sitemap['conditions'],true));

        $newCollection = [];

        foreach($collection as $sitemapItem){
            $item = $sitemapItem->getData();

            $item['url'] = $urlBuilder->getBaseUrl() . 'media/sitemap/' . $item['filename'];
            $item['updated_at'] = $dateFormat->date($item['updated_at'])->format('d/m/Y H:i:s');
            $item['created_at'] = $dateFormat->date($item['created_at'])->format('d/m/Y H:i:s');

            $newCollection[] = $item;
        }

        $variables = [
            'sitemaps' => $newCollection
        ];

        return $this->parseMustacheTemplate($this->_template, $variables);
    }

    private function getSitemapCollection($conditions = [])
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $collection = $objectManager->get('\Itatiaia\SitemapXml\Model\SitemapFactory')->create()->getResourceCollection();
        $collection->addFieldToSelect('*');
        $collection->addFieldToFilter('active', 1);
        $collection->addFieldToFilter('type', ['neq' => 'index']);
        $collection->load();

        return $collection;
    }
}
