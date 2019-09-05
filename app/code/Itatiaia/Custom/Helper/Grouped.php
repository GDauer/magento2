<?php
namespace Itatiaia\Custom\Helper;

class Grouped extends \Magento\Framework\App\Helper\AbstractHelper
{
    private $_productFactory;
    private $_scopeConfig;

    public function __construct
    (
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        $this->_scopeConfig = $scopeConfig;
        $this->_productFactory = $productFactory;
    }

    public function parseGroupedItems($groupedProduct)
    {
        $items = $groupedProduct->getTypeInstance(true)->getAssociatedProducts($groupedProduct);
        $parsedItems = [];
        $last_subgroup_item_code = null;

        for($i = 0; $i < count($items); $i++){
            $_item = $items[$i];
            $_childProduct = \Magento\Framework\App\ObjectManager::getInstance()->get('\Magento\Catalog\Model\ProductFactory')->create()->load($_item->getEntityId());

            // verifica se este item está em um subgrupo de itens
            $subgroup_item_code = $_childProduct->getData('subgroup_item_code');
            $hide_details = false;
            $first_subgroup_item = false;

            // se existe código de subgrupo
            if($subgroup_item_code){
                if($last_subgroup_item_code != $subgroup_item_code){
                    $last_subgroup_item_code = $subgroup_item_code;
                    $first_subgroup_item = true;
                    $hide_details = false;
                } else {
                    $hide_details = true;
                }
            } else {
                $last_subgroup_item_code = false;
                $first_subgroup_item = false;
                $hide_details = false;
            }

            $_item->setHideDetails($hide_details);
            $_item->setLastSubgroupItemCode($last_subgroup_item_code);
            $_item->setFirstSubgroupItem($first_subgroup_item);

            $parsedItems[] = $_item;
        }
        return $parsedItems;
    }
}
