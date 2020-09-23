<?php
/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 *
 * @copyright   2020 Webjump (http://www.webjump.com.br)
 *
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

declare(strict_types=1);

namespace Itatiaia\GroupedCatalogRule\Helper;

use Itatiaia\GroupedCatalogRule\Helper\ApplyRules;
use Magento\CatalogInventory\Api\Data\StockItemInterface;
use Magento\Framework\App\Helper\Context;
use Magento\CatalogRule\Helper\Data as HelperData;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\Registry;
use Magento\GroupedProduct\Model\Product\Type\GroupedFactory;
use Psr\Log\LoggerInterface;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Catalog\Model\Product;
use Itatiaia\GroupedCatalogRule\Model\ResourceModel\GetActiveRulesByParentProduct;

class Data extends AbstractHelper
{
    /** @var Context */
    private $context;
    /** @var HelperData */
    private $helperData;
    /** @var ProductFactory */
    private $productFactory;
    /** @var Registry */
    private $registry;
    /** @var GroupedFactory */
    private $groupedFactory;
    /** @var LoggerInterface */
    private $loggerInterface;
    /** @var StockRegistryInterface */
    private $stockRegistryInterface;
    /** @var Product */
    private $product;
    /** @var GetActiveRulesByParentProduct */
    private $getRules;
    /** @var ApplyRules */
    private $applyRules;

    /**
     * Data constructor.
     *
     * @param Context $context
     * @param HelperData $helperData
     * @param ProductFactory $productFactory
     * @param Registry $registry
     * @param GroupedFactory $groupedFactory
     * @param LoggerInterface $loggerInterface
     * @param StockRegistryInterface $stockRegistry
     * @param Product $product
     * @param GetActiveRulesByParentProduct $getRules
     * @param ApplyRules $applyRules
     */
    public function __construct(
        Context $context,
        HelperData $helperData,
        ProductFactory $productFactory,
        Registry $registry,
        GroupedFactory $groupedFactory,
        LoggerInterface $loggerInterface,
        StockRegistryInterface $stockRegistry,
        Product $product,
        GetActiveRulesByParentProduct $getRules,
        ApplyRules $applyRules
    ) {
        parent::__construct($context);
        $this->helperData = $helperData;
        $this->productFactory = $productFactory;
        $this->registry = $registry;
        $this->groupedFactory = $groupedFactory;
        $this->loggerInterface = $loggerInterface;
        $this->stockRegistryInterface;
        $this->product = $product;
        $this->getRules = $getRules;
        $this->applyRules = $applyRules;
    }

    /**
     * calculateGroupedFinalPrice method to calculate grouped final price
     *
     * @param Product $product
     *
     * @return float|int
     */
    public function calculateGroupedFinalPrice(Product $product)
    {
        $rules = $this->getRules->execute($product);

        $finalPrice = $this->getRegularPrice($product);

        return $this->applyRules->apply($rules, $finalPrice);
    }

    /**
     * calculateSimpleFinalPrice method to calculate simple final price based on parent product rule
     *
     * @param $product
     *
     * @param $parentProduct
     *
     * @return float|int
     */
    public function calculateSimpleFinalPrice($product, Product $parentProduct)
    {
        $rules = $this->getRules->execute($parentProduct);

        $finalPrice = $this->getPrice($product);

        return $this->applyRules->apply($rules, $finalPrice);
    }

    /**
     * getPrice method to get old price
     *
     * @param $product
     *
     * @return float|int
     */
    public function getPrice($product)
    {

        if($product->getTypeId() == 'grouped'){
            $price = 0;
            $associatedProducts = $product->getTypeInstance(true)->getAssociatedProducts($product);

            foreach ($associatedProducts as $item) {
                $qty = $item->getQty() ? $item->getQty() : 1;
                $childProduct = $this->productFactory->create()->load($item->getId());
                $price = $price + ((float)$childProduct->getFinalPrice() * (int)$qty);
            }
            return $price;
        }

        return $product->getPrice();
    }

    /**
     * getRegularPrice method to get regular price
     *
     * @param $product
     *
     * @return float|int
     */
    public function getRegularPrice($product)
    {
        $groupedRegularPriceRegistry = $this->registry->registry('grouped_regular_price_' . $product->getId());

        if($groupedRegularPriceRegistry){

            return $groupedRegularPriceRegistry;
        }
        $price = 0;
        if($product->getTypeId() == 'grouped') {
            $associatedProducts = $product->getTypeInstance(true)->getAssociatedProducts($product);
            foreach ($associatedProducts as $item) {
                $qty = $item->getQty() ? $item->getQty() : 1;
                $childProduct = $this->productFactory->create()->load($item->getId());
                $price = $price + ((float)$childProduct->getPrice() * (int)$qty);
            }
            $this->registry->register('grouped_regular_price_' . $product->getId(), $price);
            return $price;
        }

        $price = $product->getPrice();
        $this->registry->register('grouped_regular_price_' . $product->getId(), $price);

        return $price;
    }

    /**
     * getFinalPrice method to get final price
     *
     * @param $product
     *
     * @param null $parentProduct
     *
     * @return float|int|null
     */
    public function getFinalPrice($product, $parentProduct = null)
    {
        $finalPrice = null;
        if($product->getTypeId() == 'grouped'){
           return $finalPrice = $this->calculateGroupedFinalPrice($product);
        }

        return $finalPrice = $this->calculateSimpleFinalPrice($product, $parentProduct);
    }

    /**
     *  updateGroupedStockStatusByStockItem method
     *
     * @param StockItemInterface $stockItem
     *
     * @return void
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function updateGroupedStockStatusByStockItem(StockItemInterface $stockItem): void
    {
        $groupedProductType =$this->groupedFactory->create();
        $this->loggerInterface->info('GROUPED STOCK STATUS', [$stockItem->getData()]);

        $product = $this->productFactory->create()->load($stockItem->getProductId());
        $productId = $product->getId();
        $this->loggerInterface->info('STOCK PRODUCT ID', [$productId, $product->getName()]);

        if($product->getTypeId() == 'simple'){

            $parentProductsIds = $groupedProductType->getParentIdsByChild($productId);

            if(count($parentProductsIds)){
                $this->updateChildrenProductByParentId($parentProductsIds);
            }
        }
    }

    /**
     * isGroupedProductInStock method
     *
     * @param $groupedProduct
     *
     * @return bool
     */
    public function isGroupedProductInStock(Product $groupedProduct): bool
    {
        $childrenIds = $groupedProduct->getTypeInstance(true)->getChildrenIds($groupedProduct->getId());
        $is_in_stock = true;

        foreach($childrenIds['3'] as $k => $id){
            $childProduct = $this->productFactory->create()->load($id);

            if(!$childProduct->isAvailable()){
                $is_in_stock = false;
           }
        }

        return $is_in_stock;
    }

    /**
     * updateProductIsInStock method
     *
     * @param $product
     *
     * @param $is_in_stock
     *
     * @return void
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function updateProductIsInStock($product, $is_in_stock): void
    {
        $productSku = $product->getSku();
        $stockItem = $this->stockRegistryInterface->getStockItemBySku($productSku);
        $stockItem->setIsInStock((bool)$is_in_stock);
        $this->stockRegistryInterface->updateStockItemBySku($productSku, $stockItem);
    }

    /**
     * updateChildrenProductByParentId method
     *
     * @param $parentProductsIds
     *
     * @return void
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function updateChildrenProductByParentId($parentProductsIds): void
    {
        $groupedCollection = $this->productFactory->create()->getCollection();
        $groupedCollection->addFieldToFilter('row_id', ['in' => $parentProductsIds]);
        $groupedCollection->load();

        foreach($groupedCollection as $groupedProduct){
            $childrenIds = $groupedProduct->getTypeInstance(true)->getChildrenIds($groupedProduct->getId());

            $is_in_stock = true;
            $this->loggerInterface->info('CHILDREN IDS', [$childrenIds]);

            foreach($childrenIds['3'] as $k => $id){
                $childProduct = $this->productFactory->create()->load($id);

                $this->loggerInterface->info('CHILD ITEM', [$childProduct->getData()]);

                $extensionAttributes = $childProduct->getExtensionAttributes();
                if(!$extensionAttributes->getStockItem()->getIsInStock()){
                    $is_in_stock = false;
                }
            }
            $this->updateProductIsInStock($groupedProduct, $is_in_stock);
        }
    }
}
