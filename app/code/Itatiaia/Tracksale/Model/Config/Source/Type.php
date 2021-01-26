<?php


namespace Itatiaia\Tracksale\Model\Config\Source;


use Magento\Framework\Option\ArrayInterface;

class Type implements ArrayInterface
{

    protected $_options;

    public function toOptionArray()
    {
            $this->_options = [
                [
                    'label'=> 'Outros',
                    'value' => 1
                ],
                [
                    'label'=> 'Embedded',
                    'value' => 2
                ]
            ];

            return $this->_options;

    }
}