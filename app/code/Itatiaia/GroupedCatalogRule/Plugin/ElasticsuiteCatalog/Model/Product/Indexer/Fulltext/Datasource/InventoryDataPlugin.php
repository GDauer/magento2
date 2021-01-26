<?php
namespace Itatiaia\GroupedCatalogRule\Plugin\ElasticsuiteCatalog\Model\Product\Indexer\Fulltext\Datasource;

class InventoryDataPlugin
{
    public function afterAddData(\Smile\ElasticsuiteCatalog\Model\Product\Indexer\Fulltext\Datasource\InventoryData $subject, $result)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $productFactory = $objectManager->get('\Magento\Catalog\Model\ProductFactory');
        $logger = $objectManager->get('\Psr\Log\LoggerInterface');

        $indexData = $result;

        $total = 0;

        foreach ($indexData as $productId => $itemData) {
            if($itemData['type_id'] == 'grouped'){
                $product = $productFactory->create()->load($productId);
                if(!$product->isAvailable()){
                    $indexData[$productId]['stock']['is_in_stock'] = false;
                    $total++;
                }
            }
        }

        $logger->info('TOTAL SEM ESTOQUE', [$total]);

        return $indexData;
    }
}
