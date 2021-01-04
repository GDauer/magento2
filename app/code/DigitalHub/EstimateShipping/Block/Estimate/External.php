<?php
namespace DigitalHub\EstimateShipping\Block\Estimate;

class External extends \Magento\Framework\View\Element\Template
{
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Catalog\Helper\Image $imageHelper,
        \Magento\Theme\Block\Html\Header\Logo $logo,
        array $data = []
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->imageHelper = $imageHelper;
        $this->logo = $logo;
        parent::__construct($context, $data);
    }

    public function getLogo()
    {
        return $this->logo;
    }

    public function getProduct()
    {
        return $this->coreRegistry->registry('current_product');
    }

    public function getProductId()
    {
        return $this->coreRegistry->registry('current_product')->getId();
    }

    public function getProductImage()
    {
        return (string)$this->imageHelper->init($this->getProduct(), 'product_small_image')
                                        ->constrainOnly(false)
                                        ->keepAspectRatio(true)
                                        ->keepFrame(false)
                                        ->resize(300)
                                        ->getUrl();
    }
}
