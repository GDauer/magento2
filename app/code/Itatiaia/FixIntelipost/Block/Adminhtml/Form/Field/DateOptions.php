<?php


namespace Itatiaia\FixIntelipost\Block\Adminhtml\Form\Field;


class DateOptions extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
{

    protected $businessDaysRenderer;

    protected function _prepareToRender()
    {
        $this->addColumn(
            'business_days',
            [
                'label' => __('Dias Úteis'),
                'renderer'  => $this->getBusinessDaysRenderer(),
            ]
        );
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Adicionar dia Útil');
    }

    protected function getBusinessDaysRenderer()
    {
        if (!$this->businessDaysRenderer) {
            $this->businessDaysRenderer = $this->getLayout()->createBlock(
                '\Itatiaia\FixIntelipost\Block\Adminhtml\Form\Field\DateOption',
                'business_days',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }
        return $this->businessDaysRenderer;
    }

    protected function _prepareArrayRow(\Magento\Framework\DataObject $row)
    {
        $businessDays = $row->getData('business_days');

        $options = [];
        if ($businessDays) {
            $options['option_' . $this->getBusinessDaysRenderer()->calcOptionHash($businessDays)]
                = 'selected="selected"';
        }
        $row->setData('option_extra_attrs', $options);
    }



}