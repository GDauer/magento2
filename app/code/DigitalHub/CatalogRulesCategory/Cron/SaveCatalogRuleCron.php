<?php


namespace DigitalHub\CatalogRulesCategory\Cron;

use Magento\CatalogRule\Model\ResourceModel\Rule\CollectionFactory as RuleFactory;
use DigitalHub\CatalogRulesCategory\Helper\Data;
use Psr\Log\LoggerInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;

class SaveCatalogRuleCron
{
    /**
     * @var RuleFactory
     */
    private $ruleFactory;
    /**
     * @var Data
     */
    private $helper;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * SaveCatalogRuleCron constructor.
     * @param RuleFactory $ruleFactory
     * @param Data $helper
     * @param LoggerInterface $logger
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        RuleFactory $ruleFactory,
        Data $helper,
        LoggerInterface $logger,
        ProductRepositoryInterface $productRepository
    )
    {
        $this->ruleFactory = $ruleFactory;
        $this->helper = $helper;
        $this->logger = $logger;
        $this->productRepository = $productRepository;
    }

    /**
     * @return void
     */
    public function execute() {
        $this->logger->info('CRON TO SAVE CATALOG RULE TO SALES CATEGORY', []);
        $rules = $this->ruleFactory->create();
        $rules->addFieldToSelect('*');
        $rules->addFilter('is_active', ['eq' => '1']);
        foreach ($rules as $rule) {
            $this->logger->info('RULE: ', [$rule->getData()]);
            $productIds = $rule->getMatchingProductIds();
            foreach ($productIds as $productId => $validationByWebsite) {
                $product = $this->productRepository->getById($productId);
                if ($this->helper->validateRule($product, $rule) || $this->helper->validateProduct($product)) {
                    $this->helper->setCategory($product);
                }
            }
        }
    }
}