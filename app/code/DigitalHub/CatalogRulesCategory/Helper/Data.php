<?php


namespace DigitalHub\CatalogRulesCategory\Helper;

use Magento\Catalog\Api\CategoryLinkManagementInterface;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\CatalogRule\Api\CatalogRuleRepositoryInterface;
use Magento\CatalogRule\Model\Rule;
use Magento\Customer\Model\ResourceModel\Group\Collection as GroupCollection;
use Magento\Framework\App\Helper\AbstractHelper;
use Itatiaia\GroupedCatalogRule\Helper\Data as Helper;
use Magento\Catalog\Api\CategoryLinkRepositoryInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Store\Api\StoreRepositoryInterface;
use Psr\Log\LoggerInterface;
use Magento\CatalogRule\Model\ResourceModel\Rule as RuleResourceModel;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\CatalogRule\Helper\Data as CatalogRuleHelper;

class Data extends AbstractHelper
{
    const NOT_LOGGED_IN = "NOT LOGGED IN";

    /**
     * @var CategoryFactory
     */
    private $categoryFactory;

    /**
     * @var CategoryLinkManagementInterface
     */
    private $categoryLinkManagement;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var Helper
     */
    private $helper;

    /**
     * @var CategoryLinkRepositoryInterface
     */
    private $categoryLinkRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var RuleResourceModel
     */
    private $rule;

    /**
     * @var GroupCollection
     */
    private $customerGroupCollection;

    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * @var CatalogRuleRepositoryInterface
     */
    private $catalogRuleRepository;

    /**
     * @var CatalogRuleHelper
     */
    private $catalogRuleHelper;

    /**
     * Data constructor.
     * @param CategoryFactory $categoryFactory
     * @param CategoryLinkManagementInterface $categoryLinkManagement
     * @param ProductRepositoryInterface $productRepository
     * @param Helper $helper
     * @param CategoryLinkRepositoryInterface $categoryLinkRepository
     * @param LoggerInterface $logger
     * @param RuleResourceModel $rule
     * @param GroupCollection $customerGroupCollection
     * @param DateTime $dateTime
     * @param CatalogRuleRepositoryInterface $catalogRuleRepository
     * @param CatalogRuleHelper $catalogRuleHelper
     */
    public function __construct(
        CategoryFactory $categoryFactory,
        CategoryLinkManagementInterface $categoryLinkManagement,
        ProductRepositoryInterface $productRepository,
        Helper $helper,
        CategoryLinkRepositoryInterface $categoryLinkRepository,
        LoggerInterface $logger,
        RuleResourceModel $rule,
        GroupCollection $customerGroupCollection,
        DateTime $dateTime,
        CatalogRuleRepositoryInterface $catalogRuleRepository,
        CatalogRuleHelper $catalogRuleHelper
    )
    {
        $this->categoryFactory = $categoryFactory;
        $this->categoryLinkManagement = $categoryLinkManagement;
        $this->productRepository = $productRepository;
        $this->helper = $helper;
        $this->categoryLinkRepository = $categoryLinkRepository;
        $this->logger = $logger;
        $this->rule = $rule;
        $this->customerGroupCollection = $customerGroupCollection;
        $this->dateTime = $dateTime;
        $this->catalogRuleRepository = $catalogRuleRepository;
        $this->catalogRuleHelper = $catalogRuleHelper;
    }

    /**
     * @param Product|null $product
     * @return bool
     */
    public function validateProduct(Product $product = null, Rule $rule = null) {
        if (!empty($product) ) {
            if ($product->getTypeId() === 'grouped') {
                $oldPrice = (float) $this->helper->getRegularPrice($product);
                if (!empty($rule)) {
                    $finalPrice = $this->getGroupedFinalPrice($product, $rule);
                } else {
                    $finalPrice = (float) $this->helper->getFinalPrice($product);
                }
            } else {
                $oldPrice = (float) $product->getPrice();
                $finalPrice = (float) $product->getFinalPrice();
            }
            $discount = 0;
            if($oldPrice && $finalPrice){
                $discount = 100 - (($finalPrice * 100) / $oldPrice);
            }
            if ($discount > 10) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * @param Product|null $product
     * @param Rule|null $rule
     * @return bool
     */
    public function validateRule(Product $product = null, Rule $rule = null) {
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
                        $finalPrice = $this->getGroupedFinalPrice($product, $rule);
                    } else {
                        $finalPrice = (float) $product->getFinalPrice();
                    }
                    $discount = ($rule->getDiscountAmount() / $finalPrice) * 100;
                    break;
            }
            if ($discount > 10) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * @param Product|null $product
     */
    public function setCategory(Product $product = null) {
        if (!empty($product)) {
            $categoryName = 'Ofertas';
            $categories = $this->categoryFactory->create();
            $categories->addAttributeToSelect('*')
                ->addAttributeToFilter('name', ['like' => '%'. $categoryName . '%']);
            if ($categories->getSize() > 0) {
                $category = $categories->getFirstItem();
                $categoriesIds = $product->getCategoryIds();
                if (array_search($category->getEntityId(),$categoriesIds) === false) {
                    $categoriesIds[] = $category->getEntityId();
                    $this->categoryLinkManagement->assignProductToCategories($product->getSku(), $categoriesIds);
                    $product->setCategoryIds($categoriesIds);
                }
            }
        }
    }

    /**
     * @param Product|null $product
     */
    public function unsetCategory(Product $product = null) {
        if (!empty($product)) {
            $categoryName = 'Ofertas';
            $categories = $this->categoryFactory->create();
            $categories->addAttributeToSelect('*')
                ->addAttributeToFilter('name', ['like' => '%'. $categoryName . '%']);
            if ($categories->getSize() > 0) {
                $category = $categories->getFirstItem();
                $categoriesIds = $product->getCategoryIds();
                if (array_search($category->getEntityId(),$categoriesIds) !== false) {
                    try {
                        $this->categoryLinkRepository->deleteByIds($category->getEntityId(), $product->getSku());
                    } catch (CouldNotSaveException | StateException $e) {
                        $this->logger->error('Error in deleting product from category Ofertas', [$e->getMessage()]);
                    }
                }
            }
        }
    }

    /**
     * @param Product|null $product
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function setProductToCategory(Product $product = null) {
        if (!empty($product)) {
            $customerGroups = $this->customerGroupCollection->getItems();
            $customerGroupId = 0;
            foreach ($customerGroups as $customerGroup) {
                if ($customerGroup->getCustomerGroupCode() !== self::NOT_LOGGED_IN) {
                    $customerGroupId = $customerGroup->getCustomerGroupId();
                    break;
                }
            }
            $websitesId = $product->getWebsiteIds();

            $websiteId = reset($websitesId);;
            /**
             * @var \Magento\Framework\Stdlib\DateTime\DateTime
             */
            $dateTs = $this->dateTime->gmtDate();
            $rules = $this->rule->getRulesFromProduct($dateTs, $websiteId, $customerGroupId, $product->getEntityId());
            $ruleValidated = false;
            foreach ($rules as $item) {
                $rule = $this->catalogRuleRepository->get($item['rule_id']);
                $ruleValidated = $this->validateRule($product, $rule);
            }

            $productValidated = $this->validateProduct($product);

            if ($ruleValidated === true || $productValidated === true) {
                $this->setCategory($product);
            } else {
                $this->unsetCategory($product);
            }
        }
    }

    /**
     * Validate if it is possible to add the products related with the rule to Offers Category
     * @param Rule|null $rule
     */
    public function setRuleToCategory(Rule $rule = null) {
        if (!empty($rule)) {
            $now = $this->dateTime->gmtDate();
            if (($rule->getFromDate() == 0 || $rule->getFromDate() < $now) && ($rule->getToDate() == 0 || $rule->getToDate() > $now) && $rule->getIsActive()) {
                $productIds = $rule->getMatchingProductIds();
                foreach ($productIds as $productId => $validationByWebsite) {
                    try {
                        $product = $this->productRepository->getById($productId);
                        if ($this->validateRule($product, $rule) || $this->validateProduct($product)) {
                            $this->logger->info('Set Catalog Rule Category', [$rule->getRuleId()]);
                            $this->setCategory($product);
                        } else {
                            $this->logger->info('Remove Catalog Rule Category', [$rule->getRuleId()]);
                            $this->unsetCategory($product);
                        }
                    } catch (NoSuchEntityException $e) {
                        $this->logger->alert('Product does not exists in setRuleToCategory method', [$e->getMessage()]);
                    }

                }
            } else {
                $productIds = $rule->getMatchingProductIds();
                foreach ($productIds as $productId => $validationByWebsite) {
                    try {
                        $product = $this->productRepository->getById($productId);
                        if (!$this->validateProduct($product, $rule)) {
                            $this->logger->info('Remove Catalog Rule Category', [$rule->getRuleId()]);
                            $this->unsetCategory($product);
                        } else {
                            $this->logger->info('Set Catalog Rule Category', [$rule->getRuleId()]);
                            $this->setCategory($product);
                        }
                    } catch (NoSuchEntityException $e) {
                        $this->logger->alert('Product does not exists in setRuleToCategory method', [$e->getMessage()]);
                    }

                }
            }
        }
    }

    /**
     * @param Product $product
     * @param Rule $rule
     * @return float|int
     * @throws NoSuchEntityException
     */
    public function getGroupedFinalPrice(Product $product, Rule $rule) {
        $customerGroups = $this->customerGroupCollection->getItems();
        $customerGroupId = 0;
        foreach ($customerGroups as $customerGroup) {
            if ($customerGroup->getCustomerGroupCode() !== self::NOT_LOGGED_IN) {
                $customerGroupId = $customerGroup->getCustomerGroupId();
                break;
            }
        }
        $websitesId = $product->getWebsiteIds();

        $websiteId = reset($websitesId);;
        /**
         * @var \Magento\Framework\Stdlib\DateTime\DateTime
         */
        $dateTs = $this->dateTime->gmtDate();
        $rules = $this->rule->getRulesFromProduct($dateTs, $websiteId, $customerGroupId, $product->getEntityId());

        $finalPrice = 0;
        $associatedProducts = $product->getTypeInstance(true)->getAssociatedProducts($product);
        foreach ($associatedProducts as $item) {
            $qty = $item->getQty() ? $item->getQty() : 1;
            $childProduct = $this->productRepository->getById($item->getId());
            $finalPrice = $finalPrice + ((float)$childProduct->getFinalPrice() * (int)$qty);
        }

        if($rules){
            foreach($rules as $item){
                if ($rule->getRuleId() == $item['rule_id']) {
                    continue;
                }
                $currentRule = $this->catalogRuleRepository->get($item['rule_id']);
                $finalPrice = $this->catalogRuleHelper->calcPriceRule($currentRule->getSimpleAction(), $currentRule->getDiscountAmount(), $finalPrice);
            }
        }

        return $finalPrice;
    }
}
