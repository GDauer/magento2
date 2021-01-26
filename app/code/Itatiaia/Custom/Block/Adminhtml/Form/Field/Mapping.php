<?php
namespace Itatiaia\Custom\Block\Adminhtml\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

class Mapping extends AbstractFieldArray
{
    /**
     * {@inheritdoc}
     */
    protected function _prepareToRender()
    {
        $this->addColumn('sap', ['label' => __('SAP'), 'class' => 'required-entry']);
        $this->addColumn('intelipost', ['label' => __('Intelipost'), 'class' => 'required-entry']);
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Adicionar item');
    }
}
