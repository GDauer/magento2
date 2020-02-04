<?php
namespace Itatiaia\CatalogWidgetFilter\Plugin\Block\Product;

class ProductsListPlugin
{
    public function afterCreateCollection(\Magento\CatalogWidget\Block\Product\ProductsList $subject, $result)
    {
        $collection = $result;
        $conditions = $subject->getData('conditions_encoded');

        if ($conditions) {
        	// order collection by SKU if condition exists
	        $object_manager = \Magento\Framework\App\ObjectManager::getInstance();
	        $conditions_helper  = $object_manager->get('\Magento\Widget\Helper\Conditions');
	        $conditions = $conditions_helper->decode($conditions);
	        $skus = [];

	        foreach ($conditions as $key => $condition) {
	        	if (isset($condition['attribute']) && $condition['attribute'] == 'sku') {
	        		$skus = $condition['value'];
	        	}
	        }

	        if ($skus) {
	        	$skus = "'" . str_replace(",", "','", $skus) . "'";
				$collection->getSelect()->order(new \Zend_Db_Expr('FIELD(e.sku, ' . $skus .')'));
	        }
        }

        $collection->joinField('stock_status', 'cataloginventory_stock_status', 'stock_status', 'product_id=entity_id', '{{table}}.stock_id=1', 'left')
            ->addFieldToFilter('stock_status', array(
                    'eq' => \Magento\CatalogInventory\Model\Stock\Status::STATUS_IN_STOCK
                ));

        return $collection;
    }
}
