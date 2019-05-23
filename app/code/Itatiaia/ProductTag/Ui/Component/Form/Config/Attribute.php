<?php


namespace Itatiaia\ProductTag\Ui\Component\Form\Config;

use Magento\Framework\Data\OptionSourceInterface;

class Attribute implements OptionSourceInterface
{

    protected $options;

    protected $_attributeFactory;

    public function __construct
    (
        \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attributeFactory
    ){
        $this->_attributeFactory = $attributeFactory;
    }

    public function toOptionArray()
    {

        $attributeInfo = $this->_attributeFactory->getCollection()->addFieldToFilter('frontend_input', 'boolean');

        foreach($attributeInfo as $attribute) {
            $this->options[] = [
                'label' => $attribute->getFrontendLabel(),
                'value' => $attribute->getAttributeCode()
            ];
        }

        return $this->options;
    }

}