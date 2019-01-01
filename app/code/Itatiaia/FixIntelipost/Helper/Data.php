<?php
namespace Itatiaia\FixIntelipost\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    public function getCustomOriginZipcodeByItems(Array $items){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $config = $objectManager->get('\Magento\Framework\App\Config\ScopeConfigInterface');
        $logger = $objectManager->get('\Psr\Log\LoggerInterface');
        $defaultZipcode = $config->getValue('carriers/intelipost/source_zip');
        $zipcodeWarehouseAco = $config->getValue('carriers/itatiaia_fixintelipost/zipcode_warehouse_aco');

        $zipcode = $defaultZipcode;
        $has_material_aco = false;

        foreach($items as $item){
            $product = $objectManager->get('\Magento\Catalog\Model\Product')->load($item->getProduct()->getId());
            $material = $product->getAttributeText('material_predominante');
            if($material && ($material == 'Aço' || $material == 'aço')){
                $has_material_aco = true;
            }
            $logger->info($material);
        }

        if($has_material_aco){
            $zipcode = $zipcodeWarehouseAco;
        }

        return $zipcode;
    }
}
