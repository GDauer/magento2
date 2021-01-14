<?php
namespace DigitalHub\Banners\Block\Adminhtml\Widget;

use Magento\Framework\Data\Form\Element\AbstractElement as Element;
use Magento\Backend\Block\Template\Context as TemplateContext;
use Magento\Framework\Data\Form\Element\Factory as FormElementFactory;
use Magento\Backend\Block\Template;


class ImageChooser extends Template
{


    /**
     * @var \Magento\Framework\Data\Form\Element\Factory
     */
    protected $elementFactory;

    /**
     * @param TemplateContext $context
     * @param FormElementFactory $elementFactory
     * @param array $data
     */
    public function __construct(
        TemplateContext $context,
        FormElementFactory $elementFactory,
        $data = []
    ) {
        $this->elementFactory = $elementFactory;
        parent::__construct($context, $data);
    }

    /**
     * Prepare chooser element HTML
     *
     * @param Element $element
     * @return Element
     */
    public function prepareElementHtml(Element $element)
    {
        $config = $this->_getData('config');
        $sourceUrl = $this->getUrl('cms/wysiwyg_images/index',
            ['target_element_id' => $element->getId(), 'type' => 'file', 'use_file_url' => 1]);

        /** @var \Magento\Backend\Block\Widget\Button $chooser */
        $chooser = $this->getLayout()->createBlock('Magento\Backend\Block\Widget\Button')
            ->setType('button')
            ->setClass('btn-chooser')
            ->setLabel('Escolher imagem')
            ->setOnClick('MediabrowserUtility.openDialog(\''. $sourceUrl .'\')')
            ->setDisabled($element->getReadonly());

        $clearButton = $this->getLayout()->createBlock('Magento\Backend\Block\Widget\Button')
            ->setType('button')
            ->setClass('btn-clear')
            ->setLabel('Limpar seleção')
            ->setOnClick('jQuery("#'.$element->getId().'").val(""); jQuery(this).parents(\'.admin__field:first\').find(\'img:first\').attr(\'src\', \'\').hide()')
            ->setDisabled($element->getReadonly());

        /** @var \Magento\Framework\Data\Form\Element\Text $input */
        $input = $this->elementFactory->create("text", ['data' => $element->getData()]);
        $input->setId($element->getId());
        $input->setForm($element->getForm());
        $input->setOnchange('jQuery(this).parents(\'.admin__field:first\').find(\'img:first\').attr(\'src\', jQuery(this).val()).show()');
        $input->setClass("widget-option input-text admin__control-text");
        if ($element->getRequired()) {
            $input->addClass('required-entry');
        }

        $pre_html = '';

        $previewImg = '';
        $imgSrc = $element->getData('value');
        $element->setData('value', '');
        $previewImg = '<img style="max-width: 300px; max-height: 100px; display: '.($imgSrc ? 'block' : 'none').'" src="'.$imgSrc.'" /> <br />';

        $element->setData('after_element_html', $pre_html . $previewImg . $input->getElementHtml() . $chooser->toHtml() . $clearButton->toHtml());

        return $element;
    }

}
