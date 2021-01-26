<?php


namespace Itatiaia\GarantiaEstendida\Model\Config\Source;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\CollectionFactory as AttributeCollectionFactory;

use Magento\Framework\Option\ArrayInterface;

class SelectedProductAttribute implements ArrayInterface
{
    protected $attributeCollectionFactory;

    /**
     * SelectedProductAttribute constructor.
     * @param AttributeCollectionFactory $attributeCollectionFactory
     */
    public function __construct(AttributeCollectionFactory $attributeCollectionFactory)
    {
        $this->attributeCollectionFactory = $attributeCollectionFactory;
    }


    public function toOptionArray()
    {
        $attributes = $this->getProductAttributes();
        $result = [
            ['value'=> false, 'label' => 'Não Mapeado']
        ];

        foreach ($attributes as $attribute) {
            array_push(
                $result,
                [
                    'value' => $attribute['attribute_code'],
                    'label' => $attribute['frontend_label'] . ' (' . $attribute['attribute_code'] .')'
                ]
            );
        }

        return $result;

    }

    public function getProductAttributes()
    {
        $attributeCollection = $this->attributeCollectionFactory->create();
            $attributes = $attributeCollection->addFieldToFilter(\Magento\Eav\Model\Entity\Attribute\Set::KEY_ENTITY_TYPE_ID, 4)
                ->addFieldToFilter('source_model', 'Magento\Eav\Model\Entity\Attribute\Source\Boolean');
            return $attributes->getData();
    }

}