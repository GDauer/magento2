<?php

namespace DigitalHub\RichSnippets\Block;

use Itatiaia\GroupedCatalogRule\Helper\Data;
use Magento\Catalog\Helper\Image;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\StoreManagerInterface;

class RichSnippets extends Template
{
    /**
     * @var Registry
     */
    private $registry;

    private $product;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var Image
     */
    private $imageHelper;
    /**
     * @var Data
     */
    private $priceHelper;

    public function __construct
    (
        Data $priceHelper,
        StoreManagerInterface $storeManager,
        Image $imageHelper,
        Registry $registry,
        Template\Context $context,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->registry = $registry;
        $this->storeManager = $storeManager;
        $this->imageHelper = $imageHelper;
        $this->priceHelper = $priceHelper;
    }

    public function getProduct()
    {
        if (is_null($this->product)) {
            $this->product = $this->registry->registry('product');

            if (!$this->product->getId()) {
                throw new LocalizedException(__('Failed to initialize product'));
            }
        }

        return $this->product;
    }

    public function getIsInStock($product)
    {
        $stockData = $product->getQuantityAndStockStatus();

        if($stockData['is_in_stock']) {
            return "InStock";
        }

        return 'SoldOut';
    }

    public function getCurrency()
    {
        return $this->_storeManager->getStore()->getCurrentCurrency()->getCode();
    }

    public function getPrice($product)
    {
        if($product->getTypeId() == 'grouped')
        {
            return number_format($this->priceHelper->calculateGroupedFinalPrice($product), 2, '.','');
        }
        return number_format($product->getFinalPrice(), 2, '.','');
    }

    public function getProductImage($product)
    {
        return $this->imageHelper->init($product, 'product_base_image')->constrainOnly(FALSE)
            ->keepAspectRatio(TRUE)
            ->keepFrame(FALSE)
            ->getUrl();
    }


}