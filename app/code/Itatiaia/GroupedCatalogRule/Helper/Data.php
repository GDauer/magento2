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
            return null;
        }
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
                $childProduct = \Magento\Framework\App\ObjectManager::getInstance()->get('\Magento\Catalog\Model\Product')->load($item->getId());
                $price = $price + ((float)$childProduct->getFinalPrice() * (int)$qty);
            }
            return $price;
        }

        return $product->getFinalPrice();
    }

    // regular price
    public function getRegularPrice($product)
    {
        if($product->getTypeId() == 'grouped'){
            $price = 0;
            $associatedProducts = $product->getTypeInstance(true)->getAssociatedProducts($product);
            foreach ($associatedProducts as $item) {
                $qty = $item->getQty() ? $item->getQty() : 1;
                $childProduct = \Magento\Framework\App\ObjectManager::getInstance()->get('\Magento\Catalog\Model\Product')->load($item->getId());
                $price = $price + ((float)$childProduct->getPrice() * (int)$qty);
            }
            return $price;
        }
        return $product->getPrice();
    }

    // final price
    public function getFinalPrice($product, $parentProduct = null)
    {
        if($product->getTypeId() == 'grouped'){
            return $this->calculateGroupedFinalPrice($product);
        } else {
            return $this->calculateSimpleFinalPrice($product, $parentProduct);
        }
    }
}
