<?php
namespace DigitalHub\FeedManager\Model\Config\Source;

class Template implements \Magento\Framework\Option\ArrayInterface
{
    public function __construct(
        \DigitalHub\FeedManager\Model\Feed\TemplateFactory $feedTemplateFactory
    )
    {
        $this->feedTemplateFactory = $feedTemplateFactory;
    }

    public function toOptionArray()
    {
        $feedTemplateModel = $this->feedTemplateFactory->create();
        $collection = $feedTemplateModel
                ->getCollection();

        $options = [['label' => '', 'value' => '']];
        foreach($collection as $template){
            $options[] = [
                'label' => $template->getName(),
                'value' => $template->getId()
            ];
        }
        return $options;
    }
}
