<?php

namespace Itatiaia\CustomMercadoPago\Model\System\Config\Source\Order;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

class Response extends AbstractFieldArray
{

    protected $responseRenderer;

    protected function _prepareToRender()
    {
        $this->addColumn('status', [
            'label' => 'Resposta do Mercado Pago',
            'renderer' => $this->getResponseRender()
        ]);
        $this->addColumn('response', [
            'label' => 'Resposta',
            'class' => 'required-entry',
            'size' => '300px'
        ]);
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }

    private function getResponseRender()
    {
        if (!$this->responseRenderer) {
            $this->responseRenderer = $this->getLayout()->createBlock(
                '\Itatiaia\CustomMercadoPago\Block\Adminhtml\Form\Field\ResponseOptions',
                'status',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }
        return $this->responseRenderer;
    }

    protected function _prepareArrayRow(\Magento\Framework\DataObject $row)
    {
        $response = $row->getData('status');

        $options = [];
        if ($response) {
            $options['option_' . $this->getResponseRender()->calcOptionHash($response)]
                = 'selected="selected"';
        }
        $row->setData('option_extra_attrs', $options);
    }
}
