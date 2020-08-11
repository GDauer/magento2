<?php


namespace DigitalHub\CatalogRulesCategory\Cron;

use Magento\CatalogRule\Model\ResourceModel\Rule\CollectionFactory as RuleFactory;
use DigitalHub\CatalogRulesCategory\Helper\Data;
use Psr\Log\LoggerInterface;

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
     * SaveCatalogRuleCron constructor.
     * @param RuleFactory $ruleFactory
     * @param Data $helper
     * @param LoggerInterface $logger
     */
    public function __construct(
        RuleFactory $ruleFactory,
        Data $helper,
        LoggerInterface $logger
    )
    {
        $this->ruleFactory = $ruleFactory;
        $this->helper = $helper;
        $this->logger = $logger;
    }

    /**
     * @return void
     */
    public function execute() {
        $this->logger->info('CRON TO SAVE CATALOG RULE TO SALES CATEGORY', []);
        $rules = $this->ruleFactory->create();
        $rules->addFieldToSelect('*');
        $rules->setOrder('sort_order', 'DESC');
        foreach ($rules as $rule) {
            $this->logger->info('RULE: ', [$rule->getData()]);
            $this->helper->setRuleToCategory($rule);
        }
    }
}
