<?php


namespace DigitalHub\CatalogRulesCategory\Helper;

use Magento\Catalog\Api\CategoryLinkManagementInterface;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductFactory;
use Magento\Catalog\Model\Product;
use Magento\CatalogRule\Model\Rule;
use Magento\Framework\App\Helper\AbstractHelper;
use Itatiaia\GroupedCatalogRule\Helper\Data as Helper;

class Data extends AbstractHelper
{
    /**
     * @var CategoryFactory
     */
    private $categoryFactory;
    /**
     * @var CategoryLinkManagementInterface
     */
    private $categoryLinkManagement;
    /**
     * @var ProductFactory
     */
    private $productFactory;
    /**
     * @var Helper
     */
    private $helper;

    /**
     * Data constructor.
     * @param CategoryFactory $categoryFactory
     * @param CategoryLinkManagementInterface $categoryLinkManagement
     * @param ProductFactory $productFactory
     * @param Helper $helper
     */
    public function __construct(
        CategoryFactory $categoryFactory,
        CategoryLinkManagementInterface $categoryLinkManagement,
        ProductFactory $productFactory,
        Helper $helper
    )
    {
        $this->categoryFactory = $categoryFactory;
        $this->categoryLinkManagement = $categoryLinkManagement;
        $this->productFactory = $productFactory;
        $this->helper = $helper;
    }

    /**
     * @param Rule $rule
     */
    public function getRuleSkuToSaveInSalesCategory(Rule $rule) {
        $conditions = $rule->getConditions();
        if (!empty($conditions) && !empty($conditions->getData()) && ($conditions->getAggregator() === 'all' || $conditions->getAggregator() === 'any') && $conditions->getValue() == 1) {
            $ruleConditions = $conditions->getConditions();
            if (!empty($ruleConditions) && count($ruleConditions)) {
                foreach ($ruleConditions as $condition) {
                    if ($condition->getAttribute() === 'sku') {
                        switch ($condition->getOperator()) {
                            case '{}':
                                $sku = $condition->getValue();
                                $products = $this->productFactory->create();
                                $products->addFieldToSelect('*')
                                    ->addAttributeToFilter('sku', ['like' => '%'. $sku . '%']);
                                foreach ($products as $product) {
                                    $this->validateRule($product, $rule);
                                }
                                break;
                            case '==':
                                $sku = $condition->getValue();
                                $products = $this->productFactory->create();
                                $products->addFieldToSelect('*')
                                    ->addAttributeToFilter('sku', $sku);
                                $product = $products->getFirstItem();
                                $this->validateRule($product, $rule);
                                break;
                            case '()':
                                $skus = $condition->getValue();
                                $skus = explode(',', $skus);
                                foreach ($skus as $sku) {
                                    $products = $this->productFactory->create();
                                    $products->addFieldToSelect('*')
                                        ->addAttributeToFilter('sku', $sku);
                                    $product = $products->getFirstItem();
                                    $this->validateRule($product, $rule);
                                }
                                break;
                        }
                    }
                }
            }
        }
    }

    /**
     * @param Product|null $sku
     * @return void
     */
    public function validateProduct(Product $product = null) {
        if (!empty($product) ) {
            if ($product->getTypeId() === 'grouped') {
                $oldPrice = (float) $this->helper->getRegularPrice($product);
                $finalPrice = (float) $this->helper->getFinalPrice($product);
            } else {
                $oldPrice = (float) $product->getPrice();
                $finalPrice = (float) $product->getFinalPrice();
            }
            $discount = 0;
            if($oldPrice && $finalPrice){
                $discount = 100 - (($finalPrice * 100) / $oldPrice);
            }
            if ($discount > 10) {
                $this->setCategory($product);
            }
        }
    }

    /**
     * @param Product|null $product
     * @param Rule|null $rule
     */
    private function validateRule(Product $product = null, Rule $rule = null) {
        if (!empty($product) ) {
            switch ($rule->getSimpleAction()) {
                case 'by_percent':
                case 'to_percent':
                    $discount = $rule->getDiscountAmount();
                    break;
                case 'by_fixed':
                    if ($product->getTypeId() === 'grouped') {
                        $price = (float) $this->helper->getRegularPrice($product);
                    } else {
                        $price = (float) $product->getPrice();
                    }
                    $discount = ($rule->getDiscountAmount() / $price) * 100;
                    break;
                case 'to_fixed';
                    if ($product->getTypeId() === 'grouped') {
                        $finalPrice = (float) $this->helper->getFinalPrice($product);
                    } else {
                        $finalPrice = (float) $product->getFinalPrice();
                    }
                    $discount = ($rule->getDiscountAmount() / $finalPrice) * 100;
                    break;
            }
            if ($discount > 10) {
                $this->setCategory($product);
            }
        }
    }

    /**
     * @param Product|null $product
     */
    private function setCategory(Product $product = null) {
        $categoryName = 'Ofertas';
        $categories = $this->categoryFactory->create();
        $categories->addAttributeToSelect('*')
            ->addAttributeToFilter('name', ['like' => '%'. $categoryName . '%']);
        if ($categories->getSize() > 0) {
            $category = $categories->getFirstItem();
            $categoriesIds = $product->getCategoryIds();
            $categoriesIds[] = $category->getEntityId();
            $this->categoryLinkManagement->assignProductToCategories($product->getSku(), $categoriesIds);
        }
    }
}