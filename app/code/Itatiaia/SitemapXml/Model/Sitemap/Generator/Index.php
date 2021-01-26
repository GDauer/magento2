<?php
namespace Itatiaia\SitemapXml\Model\Sitemap\Generator;

class Index extends \Itatiaia\SitemapXml\Model\Sitemap\Generator
{
    public $_template = '<?xml version="1.0" encoding="UTF-8"?>
    <sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
        {{#sitemaps}}
        <sitemap>
            <loc>{{url}}</loc>
            <lastmod>{{updated_at}}</lastmod>
        </sitemap>
        {{/sitemaps}}
    </sitemapindex>
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

            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $logger = $objectManager->get('\Psr\Log\LoggerInterface');
//            $logger->info('ITEM INDEX', [$item]);

            $item['url'] = $urlBuilder->getBaseUrl() . 'media/sitemap/' . $item['filename'];
            $item['updated_at'] = date('d/m/Y H:i:s', strtotime($item['updated_at'] . ' -3 hours'));
            $item['created_at'] = date('d/m/Y H:i:s', strtotime($item['created_at'] . ' -3 hours'));

//            $logger->info('ITEM INDEX', [$item]);

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
