<?php


namespace DigitalHub\CatalogRulesCategory\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use DigitalHub\CatalogRulesCategory\Helper\Data;


class SaveCatalogRuleObserver implements ObserverInterface
{

    /**
     * @var Data
     */
    private $helper;

    /**
     * SaveCategoryObserver constructor.
     * @param Data $helper
     */
    public function __construct(
        Data $helper
    )
    {
        $this->helper = $helper;
    }

    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        $rule = $observer->getRule();
        $this->helper->getRuleSkuToSaveInSalesCategory($rule);
    }
}