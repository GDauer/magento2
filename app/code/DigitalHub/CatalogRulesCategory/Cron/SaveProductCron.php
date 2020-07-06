<?php


namespace DigitalHub\CatalogRulesCategory\Cron;


use DigitalHub\CatalogRulesCategory\Helper\Data;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductFactory;
use Magento\CatalogInventory\Helper\Stock;
use Psr\Log\LoggerInterface;

class SaveProductCron
{
    /**
     * @var ProductFactory
     */
    private $productFactory;
    /**
     * @var Data
     */
    private $helper;
    private $stockFilter;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * SaveCategoryCron constructor.
     * @param ProductFactory $productFactory
     * @param Data $helper
     * @param LoggerInterface $logger
     */
    public function __construct(
        ProductFactory $productFactory,
        Data $helper,
        Stock $stockFilter,
        LoggerInterface $logger
    )
    {
        $this->productFactory = $productFactory;
        $this->helper = $helper;
        $this->stockFilter = $stockFilter;
        $this->logger = $logger;
    }

    /**
     * @return void
     */
    public function execute() {
        $this->logger->info('CRON TO SAVE PRODUCT TO SALES CATEGORY', []);
        $products = $this->productFactory->create();
        $products->addFieldToSelect('*')
            ->addAttributeToFilter(
                'status', array('eq' => \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED)
            );
        $this->stockFilter->addInStockFilterToCollection($products);
        foreach ($products as $product) {
            if($product->getSpecialPrice() > 0 || count($product->getTierPrice()) > 0) {
                $this->logger->info('PRODUCT: ', [$product->getData()]);
                $this->helper->setProductToCategory($product);
            }
        }
    }
}