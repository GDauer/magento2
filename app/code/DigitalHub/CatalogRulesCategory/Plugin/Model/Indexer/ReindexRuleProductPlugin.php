<?php


namespace DigitalHub\CatalogRulesCategory\Plugin\Model\Indexer;

/**
 * This plugin gets the schedules catalog rules and add the product(s) related to it to the Ofertas category
 */

use Magento\CatalogRule\Model\Indexer\ReindexRuleProduct;
use Magento\CatalogRule\Model\Rule;
use DigitalHub\CatalogRulesCategory\Helper\Data;
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
     * ReindexRuleProductPlugin constructor.
     * @param LoggerInterface $logger
     * @param Data $helper
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        LoggerInterface $logger,
        Data $helper
    ) {
        $this->logger = $logger;
        $this->helper = $helper;
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
        $this->helper->setRuleToCategory($rule);
        $this->logger->info('Rule', [$rule->getData()]);

        return $result;
    }
}
