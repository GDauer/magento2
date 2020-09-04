<?php


namespace Itatiaia\FixIntelipost\Block\Adminhtml\Form\Field;


class DisabledDateOptions extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
{

    protected $businessDaysRenderer;

    protected function _prepareToRender()
    {
        $this->addColumn(
            'disabled_days',
            [
                'label' => __('Datas')
            ]
        );
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Adicionar data Desabilitada');
    }

}