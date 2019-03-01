<?php
namespace Itatiaia\SitemapXml\Model\Sitemap\Generator;

class Cms extends \Itatiaia\SitemapXml\Model\Sitemap\Generator
{
    public $_template = '<?xml version="1.0" encoding="UTF-8"?>
        <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
            {{#sitemaps}}
                <url>
                    <loc>{{url}}</loc>
                    <lastmod>{{update_time}}</lastmod>
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

        foreach($collection as $page){
            $item = $page->getData();

            $item['url'] = $urlBuilder->getBaseUrl() . $item['identifier'];
            $item['update_time'] = $dateFormat->date($item['update_time'])->format('d/m/Y H:i:s');
            $item['creation_time'] = $dateFormat->date($item['creation_time'])->format('d/m/Y H:i:s');

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

        $collection = $objectManager->get('\Magento\Cms\Model\PageFactory')->create()->getResourceCollection();
        $collection->addFieldToSelect('*');
        $collection->addFieldToFilter('is_active', 1);
        $collection->load();

        return $collection;
    }
}
