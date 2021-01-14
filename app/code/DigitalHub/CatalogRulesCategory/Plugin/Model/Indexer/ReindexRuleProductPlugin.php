<?php


namespace DigitalHub\CatalogRulesCategory\Plugin\Model\Indexer;

/**
 * This plugin gets the schedules catalog rules and add the product(s) related to it to the Ofertas category
 */

use Magento\CatalogRule\Model\Indexer\ReindexRuleProduct;
use Magento\CatalogRule\Model\Rule;
use DigitalHub\CatalogRulesCategory\Helper\Data;
use Psr\Log\LoggerInterface;
use Magento\Framework\Registry;

class ReindexRuleProductPlugin
{
    const RULE_PROCESS_LIST = 'rule_processed_list';
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Data
     */
    private $helper;

    /** @var Registry  */
    private $registry;

    /**
     * ReindexRuleProductPlugin constructor.
     * @param LoggerInterface $logger
     * @param Data $helper
     * @param Registry $registry
     */
    public function __construct(
        LoggerInterface $logger,
        Data $helper,
        Registry $registry
    ) {
        $this->logger = $logger;
        $this->helper = $helper;
        $this->registry = $registry;
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
        $processedList = $this->registry->registry(self::RULE_PROCESS_LIST);
        $isRegistryExist = true;
        if (!$processedList) {
            $processedList = [];
            $isRegistryExist = false;
        }

        if (in_array($rule->getRuleId(), $processedList)) {
            return $result;
        }

        $this->helper->setRuleToCategory($rule);
        $this->logger->info('Rule', [$rule->getRuleId()]);
        $processedList[] = $rule->getRuleId();
        if ($isRegistryExist) {
            $this->registry->unregister(self::RULE_PROCESS_LIST);
        }
        $this->registry->register(self::RULE_PROCESS_LIST, $processedList);
        return $result;
    }
}
