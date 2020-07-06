<?php


namespace DigitalHub\CatalogRulesCategory\Plugin\Model\Indexer;

/**
 * This plugin gets the schedules catalog rules and add the product(s) related to it to the Ofertas category
 */

use Magento\CatalogRule\Model\Indexer\ReindexRuleProduct;
use Magento\CatalogRule\Model\Rule;
use DigitalHub\CatalogRulesCategory\Helper\Data;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Psr\Log\LoggerInterface;

class ReindexRuleProductPlugin
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Data
     */
    private $helper;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * ReindexRuleProductPlugin constructor.
     * @param LoggerInterface $logger
     * @param Data $helper
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        LoggerInterface $logger,
        Data $helper,
        ProductRepositoryInterface $productRepository
    ) {
        $this->logger = $logger;
        $this->helper = $helper;
        $this->productRepository = $productRepository;
    }

    /**
     * @param ReindexRuleProduct $subject
     * @param bool $result
     * @param Rule $rule
     * @param $batchCount
     * @param bool $useAdditionalTable
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function afterExecute(
        ReindexRuleProduct $subject,
        bool $result,
        Rule $rule,
        $batchCount,
        $useAdditionalTable = false
    ) {
        if ($rule->getIsActive()) {
            $productIds = $rule->getMatchingProductIds();
            foreach ($productIds as $productId => $validationByWebsite) {
                $product = $this->productRepository->getById($productId);
                if ($this->helper->validateRule($product, $rule) || $this->helper->validateProduct($product)) {
                    $this->helper->setCategory($product);
                }
            }
        } else {
            $productIds = $rule->getMatchingProductIds();
            foreach ($productIds as $productId => $validationByWebsite) {
                $product = $this->productRepository->getById($productId);
                $validateRule = $this->helper->validateRule($product, $rule);
                $validateProduct = $this->helper->validateProduct($product);
                if (!$this->helper->validateProduct($product)) {
                    $this->helper->unsetCategory($product);
                }
                $this->logger->info('$validateRule', [$validateRule]);
                $this->logger->info('$validateProduct', [$validateProduct]);
            }
        }
        $this->logger->info('Rule', [$rule->getData()]);

        return $result;
    }
}