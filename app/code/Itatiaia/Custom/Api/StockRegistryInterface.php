<?php


namespace Itatiaia\Custom\Api;


use Magento\Framework\Registry;
use Magento\Catalog\Model\ProductFactory;

class StockRegistryInterface
{

    /**
     * @var Registry
     */
    private $registry;
    /**
     * @var ProductFactory
     */
    private $productFactory;

    public function __construct
    (
        Registry $registry,
        ProductFactory $productFactory
    )
    {
        $this->registry = $registry;

        $this->productFactory = $productFactory;
    }

    public function afterUpdateStockItemBySku(
        \Magento\CatalogInventory\Api\StockRegistryInterface $subject,
        $itemId
    )
    {
        if($this->registry->registry('update_product_after_stock_item_id') === $itemId){
            return $itemId;
        }

        $product = $this->productFactory->create()->load($itemId);

        $this->registry->register('update_product_after_stock_item_id', $itemId);
        $product->save();

        return $itemId;
    }
}