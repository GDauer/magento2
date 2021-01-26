<?php


namespace Itatiaia\ProductTag\Ui\Component\Form\Config;

use Magento\Framework\Data\OptionSourceInterface;

class Position implements OptionSourceInterface
{

    protected $options;

    public function toOptionArray()
    {

        $this->options = [
            [
                'label' => 'Top-Right',
                'value' => '1'
            ],
            [
                'label' => 'Bottom',
                'value' => '2'
            ]
        ];

        return $this->options;
    }

}