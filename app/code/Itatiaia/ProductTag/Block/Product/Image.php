<?php


namespace Itatiaia\ProductTag\Block\Product;

use Magento\Framework\View\Element\Template;

class Image extends Template
{
    protected $collectionFactory;
    protected $productFactory;
    protected $storeManager;

    public function __construct(
        Template\Context $context,
        \Itatiaia\ProductTag\Model\Resource\Tag\CollectionFactory $collectionFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->productFactory = $productFactory;
        $this->storeManager = $storeManager;

        parent::__construct($context);
    }

    public function getTagImage($tag)
    {
        return $this->getMediaUrl().$tag->getImage();
    }

    public function getTagPosition($tag)
    {
        if($tag->getPosition() == 1)
        {
            return 'top-right';
        }
        return 'bottom';
    }

    public function getProductTag($product)
    {
        $data = $product->getData();
        $tags = $this->collectionFactory->create()->load();
        $productTags = [];
        foreach ($tags as $tag)
        {
            if($tag->getIsActive()){
                if(isset($data[$tag->getAttributeCode()])
                    && $data[$tag->getAttributeCode()] == 1)
                {
                    $productTags[] = $tag;
                }
            }
        }
        return $productTags;
    }

    public function getMediaUrl()
    {
        $mediaUrl = $this->storeManager->getStore()
                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'tag/tmp/icon/';
        return $mediaUrl;
    }

}