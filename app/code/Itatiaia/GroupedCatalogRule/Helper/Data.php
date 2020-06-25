<?php
namespace Itatiaia\GroupedCatalogRule\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    public function getActiveRulesByParentProduct($product)
    {
        // buscar pelo ID do produto agrupado
        // comparar se está ativa (consulta regra pai em catalogrule)
        // comparar data para filtragem
        // comparar customer group
        // comparar sobreposição de regras (!!)
    /*    if (!$product) {
            return null;
        }*/
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $config = \Magento\Framework\App\ObjectManager::getInstance()->get('\Magento\Framework\App\Config\ScopeConfigInterface');

        if($config->getValue('sales/itatiaia_groupedcatalogrule/active')){
            $logger = $objectManager->get('\Psr\Log\LoggerInterface');
            $customerSession = $objectManager->get('\Magento\Customer\Model\SessionFactory')->create();

            $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
            $connection = $resource->getConnection();

            $catalogrule_table = $resource->getTableName('catalogrule');
            $catalogrule_product_table = $resource->getTableName('catalogrule_product');

            $customer_group_id = $customerSession->getCustomerGroupId();

            //Select Data from table
            $sql = "select rule_product.*, rule.is_active as is_active from " . $catalogrule_product_table . " as rule_product, ".$catalogrule_table." as rule where rule.rule_id = rule_product.rule_id and rule.is_active = 1 and customer_group_id = ".$customer_group_id." and product_id = " . $product->getId() . " and from_time <= " . time() . " and (to_time = 0 OR to_time >= " . time() . ") group by rule_id order by sort_order ASC";
            // $logger->info('SQL Rules', [$sql]);

            $result = $connection->fetchAll($sql); // gives associated array, table fields as key in array.
            // $logger->info('SQL Rules Result', $result);
            if(count($result)){
                return $result;
            }
        }
        return null;
    }

    //
    public function calculateGroupedFinalPrice($product)
    {
        // calcular final price para simples baseado em regra existentes (getActiveRulesByParentProduct)
        $rules = $this->getActiveRulesByParentProduct($product);

        $finalPrice = $this->getPrice($product);

        if($rules){
            $catalogRuleHelper = \Magento\Framework\App\ObjectManager::getInstance()->get('\Magento\CatalogRule\Helper\Data');
            foreach($rules as $rule){
                $finalPrice = $catalogRuleHelper->calcPriceRule($rule['action_operator'], $rule['action_amount'], $finalPrice);
                if((int)$rule['action_stop']){
                    break;
                }
            }
        }

        return $finalPrice;
    }

    public function calculateSimpleFinalPrice($product, $parentProduct)
    {
        // calcular final price para simples baseado em regra existentes do produto pai (getActiveRulesByParentProduct)
        $rules = $this->getActiveRulesByParentProduct($parentProduct);

        $finalPrice = $this->getPrice($product);

        if($rules){
            $catalogRuleHelper = \Magento\Framework\App\ObjectManager::getInstance()->get('\Magento\CatalogRule\Helper\Data');
            foreach($rules as $rule){
                $finalPrice = $catalogRuleHelper->calcPriceRule($rule['action_operator'], $rule['action_amount'], $finalPrice);
                if((int)$rule['action_stop']){
                    break;
                }
            }
        }

        return $finalPrice;
    }

    // old price
    public function getPrice($product)
    {
        if($product->getTypeId() == 'grouped'){
            $price = 0;
            $associatedProducts = $product->getTypeInstance(true)->getAssociatedProducts($product);
            foreach ($associatedProducts as $item) {
                $qty = $item->getQty() ? $item->getQty() : 1;
                $childProduct = \Magento\Framework\App\ObjectManager::getInstance()->get('\Magento\Catalog\Model\ProductFactory')->create()->load($item->getId());
                $price = $price + ((float)$childProduct->getFinalPrice() * (int)$qty);
            }
            return $price;
        }

        return $product->getPrice();
    }

    // regular price
    public function getRegularPrice($product)
    {
        // \Magento\Framework\App\ObjectManager::getInstance()->get('\Psr\Log\LoggerInterface')->info('GROUPED GET REGULAR PRICE');
        $registry = \Magento\Framework\App\ObjectManager::getInstance()->get('\Magento\Framework\Registry');

        // se já está calculado, retorna
        if($registry->registry('grouped_regular_price_' . $product->getId())){
            // \Magento\Framework\App\ObjectManager::getInstance()->get('\Psr\Log\LoggerInterface')->info('RETORNANDO JÀ EXISTENTE');
            return $registry->registry('grouped_regular_price_' . $product->getId());
        }

        $price = 0;
        if($product->getTypeId() == 'grouped'){
            $associatedProducts = $product->getTypeInstance(true)->getAssociatedProducts($product);
            foreach ($associatedProducts as $item) {
                $qty = $item->getQty() ? $item->getQty() : 1;
                $childProduct = \Magento\Framework\App\ObjectManager::getInstance()->get('\Magento\Catalog\Model\ProductFactory')->create()->load($item->getId());
                $price = $price + ((float)$childProduct->getPrice() * (int)$qty);
            }
        } else {
            $price = $product->getPrice();
        }

        $registry->register('grouped_regular_price_' . $product->getId(), $price);

        return $price;
    }

    // final price
    public function getFinalPrice($product, $parentProduct = null)
    {
        // \Magento\Framework\App\ObjectManager::getInstance()->get('\Psr\Log\LoggerInterface')->info('GROUPED GET FINAL PRICE');
        $registry = \Magento\Framework\App\ObjectManager::getInstance()->get('\Magento\Framework\Registry');

        // se já está calculado, retorna
        if($registry->registry('grouped_final_price_' . $product->getId())){
            // \Magento\Framework\App\ObjectManager::getInstance()->get('\Psr\Log\LoggerInterface')->info('RETORNANDO JÀ EXISTENTE');
            return $registry->registry('grouped_final_price_' . $product->getId());
        }

        $finalPrice = null;
        if($product->getTypeId() == 'grouped'){
            $finalPrice = $this->calculateGroupedFinalPrice($product);
        } else {
            $finalPrice = $this->calculateSimpleFinalPrice($product, $parentProduct);
        }

        $registry->register('grouped_final_price_' . $product->getId(), $finalPrice);

        return $finalPrice;
    }


    public function updateGroupedStockStatusByStockItem($stockItem)
    {
        $groupedProductType = \Magento\Framework\App\ObjectManager::getInstance()->get('\Magento\GroupedProduct\Model\Product\Type\GroupedFactory')->create();
        $productFactory = \Magento\Framework\App\ObjectManager::getInstance()->get('\Magento\Catalog\Model\ProductFactory');
        $logger = \Magento\Framework\App\ObjectManager::getInstance()->get('\Psr\Log\LoggerInterface');
        $logger->info('GROUPED STOCK STATUS', [$stockItem->getData()]);

        // busca produtos agrupados relacionados
        // para cada agrupado encontrado, busca pelos filhos
            // verifica o status do estoque de cada item
            // verifica se exite um dos itens do agrupado sem estoque
            // altera status de stock do agrupado para sem estoque

        $product = $productFactory->create()->load($stockItem->getProductId());
        $logger->info('STOCK PRODUCT ID', [$product->getId(), $product->getName()]);

        if($product->getTypeId() == 'simple'){
            // busca produtos agrupados relacionados
            $parentProductsIds = $groupedProductType->getParentIdsByChild($product->getId());

            if(count($parentProductsIds)){
                $groupedCollection = $productFactory->create()->getCollection();
                $groupedCollection->addFieldToFilter('row_id', ['in' => $parentProductsIds]);
                $groupedCollection->load();

                // para cada agrupado encontrado, busca pelos filhos
                foreach($groupedCollection as $groupedProduct){
                    $childrenIds = $groupedProduct->getTypeInstance(true)->getChildrenIds($groupedProduct->getId());

                    $is_in_stock = true;
                    $logger->info('CHILDREN IDS', [$childrenIds]);

                    // Link Type ID = 3
                    // verifica o status do estoque de cada item
                    foreach($childrenIds['3'] as $k => $id){
                        $childProduct = $productFactory->create()->load($id);

                        // verifica se exite um dos itens do agrupado sem estoque
                        $logger->info('CHILD ITEM', [$childProduct->getData()]);

                        $extensionAttributes = $childProduct->getExtensionAttributes();
                        if(!$extensionAttributes->getStockItem()->getIsInStock()){
                            $is_in_stock = false;
                        }
                    }

                    // update stock item do produto agrupado
                    $this->updateProductIsInStock($groupedProduct, $is_in_stock);
                }
            }
        }
    }

    public function isGroupedProductInStock($groupedProduct)
    {
        $productFactory = \Magento\Framework\App\ObjectManager::getInstance()->get('\Magento\Catalog\Model\ProductFactory');
        $childrenIds = $groupedProduct->getTypeInstance(true)->getChildrenIds($groupedProduct->getId());
        $is_in_stock = true;

        // Link Type ID = 3
        // verifica o status do estoque de cada item
        foreach($childrenIds['3'] as $k => $id){
            $childProduct = $productFactory->create()->load($id);

            // // verifica se exite um dos itens do agrupado sem estoque
            // $extensionAttributes = $childProduct->getExtensionAttributes();
            // if(!$extensionAttributes->getStockItem()->getIsInStock()){
            //     $is_in_stock = false;
            // }
            if(!$childProduct->isAvailable()){
                $is_in_stock = false;
            }
        }

        return $is_in_stock;
    }

    public function updateProductIsInStock($product, $is_in_stock) {
        $stockRegistry = \Magento\Framework\App\ObjectManager::getInstance()->get('\Magento\CatalogInventory\Api\StockRegistryInterface');
        $stockItem = $stockRegistry->getStockItemBySku($product->getSku());
        $stockItem->setIsInStock((bool)$is_in_stock);
        $stockRegistry->updateStockItemBySku($product->getSku(), $stockItem);
    }
}
