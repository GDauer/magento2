<?php


namespace DigitalHub\CatalogRulesCategory\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use DigitalHub\CatalogRulesCategory\Helper\Data;

class SaveProductObserver implements ObserverInterface
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
        $product = $observer->getProduct();
        $this->helper->validateProduct($product);
    }
}