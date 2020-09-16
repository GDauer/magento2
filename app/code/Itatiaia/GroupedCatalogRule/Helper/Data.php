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

use Magento\Framework\App\Helper\Context;
use Magento\CatalogRule\Helper\Data as HelperData;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Customer\Model\SessionFactory;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\Registry;
use Magento\GroupedProduct\Model\Product\Type\GroupedFactory;
use Psr\Log\LoggerInterface;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Catalog\Model\Product;


class Data extends AbstractHelper
{
    /** @var Context */
    private $context;
    /** @var HelperData */
    private $helperData;
    /** @var ResourceConnection */
    private $resourceConnection;
    /** @var SessionFactory */
    private $sessionFactory;
    /** @var ProductFactory */
    private $productfactory;
    /** @var Registry */
    private $registry;
    /** @var GroupedFactory */
    private $groupedFactory;
    /** @var LoggerInterface */
    private $loggerInterface;
    /** @var StockRegistryInterface */
    private $stockRegistryInterface;
    private $product;
    /**
     * Data constructor.
     *
     * @param Context $context
     * @param HelperData $helperData
     * @param ResourceConnection $resourceConnection
     * @param SessionFactory $sessionFactory
     * @param ProductFactory $productFactory
     * @param Registry $registry
     * @param GroupedFactory $groupedFactory
     * @param LoggerInterface $loggerInterface
     * @param StockRegistryInterface $stockRegistry
     */
    public function __construct(
        Context $context,
        HelperData $helperData,
        ResourceConnection $resourceConnection,
        SessionFactory $sessionFactory,
        ProductFactory $productFactory,
        Registry $registry,
        GroupedFactory $groupedFactory,
        LoggerInterface $loggerInterface,
        StockRegistryInterface $stockRegistry,
        Product $product
    ) {
        parent::__construct($context);
        $this->helperData = $helperData;
        $this->resourceConnection = $resourceConnection;
        $this->sessionFactory = $sessionFactory;
        $this->productfactory = $productFactory;
        $this->registry = $registry;
        $this->groupedFactory = $groupedFactory;
        $this->loggerInterface = $loggerInterface;
        $this->stockRegistryInterface;
        $this->product = $product;
    }

    /**
     * getActiveRulesByParentProduct method to get catalog rules
     *
     * @param $product
     *
     * @return null
     */
    public function getActiveRulesByParentProduct($product)
    {
        if($this->scopeConfig->getValue('sales/itatiaia_groupedcatalogrule/active')){
            $customerSession = $this->sessionFactory->create();

            $connection = $this->resourceConnection->getConnection();

            $catalogrule_table = $this->resourceConnection->getTableName('catalogrule');
            $catalogrule_product_table = $this->resourceConnection->getTableName('catalogrule_product');

            $customer_group_id = $customerSession->getCustomerGroupId();

            $sql = "select rule_product.*, rule.is_active as is_active from " . $catalogrule_product_table . " as rule_product,
             ".$catalogrule_table." as rule 
             where rule.rule_id = rule_product.rule_id
             and rule.is_active = 1
             and customer_group_id = ".$customer_group_id."
             and product_id = " . $product->getId() . "
             and from_time <= " . time() . "
             and (to_time = 0 OR to_time >= " . time() . ")
             group by rule_id
             order by sort_order ASC";

            $result = $connection->fetchAll($sql);

            if(count($result)){
                return $result;
            }
        }
        return null;
    }

    /**
     * calculatedGroupedFinalPrice method
     *
     * @param $product
     *
     * @return float|int
     */
    public function calculateGroupedFinalPrice(Product $product)
    {
        $rules = $this->getActiveRulesByParentProduct($product);
        $finalPrice = $this->getRegularPrice($product);

        if($rules){
            foreach($rules as $rule){
                $finalPrice = $this->helperData->calcPriceRule($rule['action_operator'], $rule['action_amount'], $finalPrice);
                if((int)$rule['action_stop']){
                    break;
                }
            }
        }

        return $finalPrice;
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
        $rules = $this->getActiveRulesByParentProduct($parentProduct);

        $finalPrice = $this->getPrice($product);

        if($rules){
            foreach($rules as $rule){
                $finalPrice = $this->helperData->calcPriceRule($rule['action_operator'], $rule['action_amount'], $finalPrice);
                if((int)$rule['action_stop']){
                    break;
                }
            }
        }

        return $finalPrice;
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
                $childProduct = $this->productfactory->create()->load($item->getId());
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
        if($this->registry->registry('grouped_regular_price_' . $product->getId())){

            return $this->registry->registry('grouped_regular_price_' . $product->getId());
        }

        $price = 0;
        if($product->getTypeId() == 'grouped'){
            $associatedProducts = $product->getTypeInstance(true)->getAssociatedProducts($product);
            foreach ($associatedProducts as $item) {
                $qty = $item->getQty() ? $item->getQty() : 1;
                $childProduct = $this->productfactory->create()->load($item->getId());
                $price = $price + ((float)$childProduct->getPrice() * (int)$qty);
            }
        } else {
            $price = $product->getPrice();
        }

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
            $finalPrice = $this->calculateGroupedFinalPrice($product);
        } else {
            $finalPrice = $this->calculateSimpleFinalPrice($product, $parentProduct);
        }

        return $finalPrice;
    }

    /**
     * updateGroupedStockStatusByStockItem method to update stock status based on item status
     *
     * @param $stockItem
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function updateGroupedStockStatusByStockItem($stockItem)
    {
        $groupedProductType =$this->groupedFactory->create();
        $this->loggerInterface->info('GROUPED STOCK STATUS', [$stockItem->getData()]);

        $product = $this->productfactory->create()->load($stockItem->getProductId());
        $this->loggerInterface->info('STOCK PRODUCT ID', [$product->getId(), $product->getName()]);

        if($product->getTypeId() == 'simple'){

            $parentProductsIds = $groupedProductType->getParentIdsByChild($product->getId());

            if(count($parentProductsIds)){
                $groupedCollection = $this->productfactory->create()->getCollection();
                $groupedCollection->addFieldToFilter('row_id', ['in' => $parentProductsIds]);
                $groupedCollection->load();

                foreach($groupedCollection as $groupedProduct){
                    $childrenIds = $groupedProduct->getTypeInstance(true)->getChildrenIds($groupedProduct->getId());

                    $is_in_stock = true;
                    $this->loggerInterface->info('CHILDREN IDS', [$childrenIds]);

                    foreach($childrenIds['3'] as $k => $id){
                        $childProduct = $this->productfactory->create()->load($id);

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
    }

    /**
     * isGroupedProductInStock method
     *
     * @param $groupedProduct
     *
     * @return bool
     */
    public function isGroupedProductInStock($groupedProduct): bool
    {
        $childrenIds = $groupedProduct->getTypeInstance(true)->getChildrenIds($groupedProduct->getId());
        $is_in_stock = true;

        foreach($childrenIds['3'] as $k => $id){
            $childProduct = $this->productfactory->create()->load($id);

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
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function updateProductIsInStock($product, $is_in_stock) {

        $stockItem = $this->stockRegistryInterface->getStockItemBySku($product->getSku());
        $stockItem->setIsInStock((bool)$is_in_stock);
        $this->stockRegistryInterface->updateStockItemBySku($product->getSku(), $stockItem);
    }
}
