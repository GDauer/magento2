<?php
namespace Itatiaia\SitemapXml\Helper;

class Sitemap extends \Magento\Framework\App\Helper\AbstractHelper
{
    public function generateSitemap($sitemap)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $directory_list = $objectManager->get('\Magento\Framework\App\Filesystem\DirectoryList');
        $logger = $objectManager->get('\Psr\Log\LoggerInterface');

        // start emulation
        $emulation = $objectManager->get('\Magento\Store\Model\App\Emulation');
        $emulation->startEnvironmentEmulation($sitemap->getStoreId(), 'frontend', true);

        try {
            $xml = $this->generateXml($sitemap);

            $sitemap_dir = $directory_list->getPath('media') . '/sitemap';
            $file = $sitemap_dir . '/' . $sitemap->getFilename();

            if(!file_exists($sitemap_dir)){
                mkdir($sitemap_dir);
                chmod($sitemap_dir, 0777);
            }

            file_put_contents($file, $xml);

            $sitemap->setStatus('generated');
            $sitemap->setGeneratedAt(date('Y-m-d H:i:s'));
            $sitemap->save();
        } catch (Exception $e){
            throw new Exception($e->getMessage());
        }

        // end emulation
        $emulation->stopEnvironmentEmulation();
    }

    public function generateXml($sitemap)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        if($sitemap->getType() == 'products'){
            $generator = $objectManager->get('\Itatiaia\SitemapXml\Model\Sitemap\Generator\Products');
            return $generator->generateXml($sitemap);
        }

        if($sitemap->getType() == 'categories'){
            $generator = $objectManager->get('\Itatiaia\SitemapXml\Model\Sitemap\Generator\Categories');
            return $generator->generateXml($sitemap);
        }

        if($sitemap->getType() == 'cms'){
            $generator = $objectManager->get('\Itatiaia\SitemapXml\Model\Sitemap\Generator\Cms');
            return $generator->generateXml($sitemap);
        }

        if($sitemap->getType() == 'index'){
            $generator = $objectManager->get('\Itatiaia\SitemapXml\Model\Sitemap\Generator\Index');
            return $generator->generateXml($sitemap);
        }

        if($sitemap->getType() == 'product_images'){
            $generator = $objectManager->get('\Itatiaia\SitemapXml\Model\Sitemap\Generator\ProductImages');
            return $generator->generateXml($sitemap);
        }

        return null;
    }
}
