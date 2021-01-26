<?php


namespace DigitalHub\ProductAlertReport\Block\Widget\Grid\Column\Renderer;

use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Framework\DataObject;

class Status extends AbstractRenderer
{
    /**
     * State constructor.
     * @param \Magento\Backend\Block\Context $context
     * @param array $data
     */
    public function __construct(\Magento\Backend\Block\Context $context, array $data = [])
    {
        parent::__construct($context, $data);
    }

    /**
     * Renders grid column
     *
     * @param DataObject $row
     * @return mixed|string
     */
    public function render(DataObject $row)
    {
        $result = '';
        $status = $row->getData('status');
        if ($status === '1') {
            $result = 'Enviado';
        } else {
            $result = 'Não Enviado';
        }

        return $result;
    }
}