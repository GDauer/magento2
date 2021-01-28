<?php
namespace DigitalHub\FeedManager\Model\Config\Source;

class Attribute implements \Magento\Framework\Option\ArrayInterface
{
    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $collectionFactory
    )
    {
        $this->collectionFactory = $collectionFactory;
    }

    public function toOptionArray()
    {
        $collection = $this->collectionFactory->create();
        $options = [];
        foreach($collection as $attribute){
            if($attribute->getData('is_system') != 1){
                $options[] = [
                    'label' => $attribute->getFrontendLabel(),
                    'value' => $attribute->getAttributeCode()
                ];
            }
        }
        return $options;
    }
}
