<?php


namespace Itatiaia\SitemapXml\Cron;


use Itatiaia\SitemapXml\Helper\Sitemap;
use Itatiaia\SitemapXml\Model\ResourceModel\Sitemap\CollectionFactory;
use Psr\Log\LoggerInterface;

class Generate
{

    /**
     * @var Sitemap
     */
    private $sitemapHelper;
    /**
     * @var CollectionFactory
     */
    private $sitemapCollectionFactory;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        Sitemap $sitemapHelper,
        CollectionFactory $sitemapCollectionFactory,
        LoggerInterface $logger
    )
    {
        $this->sitemapHelper = $sitemapHelper;
        $this->sitemapCollectionFactory = $sitemapCollectionFactory;
        $this->logger = $logger;
    }

    public function execute()
    {
//        $this->logger->info('CRON SITEMAP', []);
        $collection = $this->sitemapCollectionFactory->create();
        foreach ($collection as $sitemap){
            $this->sitemapHelper->generateSitemap($sitemap);
        }
    }
}