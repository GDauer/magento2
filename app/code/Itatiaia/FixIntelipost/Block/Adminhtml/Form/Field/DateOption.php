<?php


namespace Itatiaia\FixIntelipost\Block\Adminhtml\Form\Field;


class DateOption extends \Magento\Framework\View\Element\Html\Select
{
    /**
     * Options
     *
     * @var array
     */
    protected $_options = [
        '1' => 'Segunda-Feira',
        '2' => 'Terça-Feira',
        '3' => 'Quarta-Feira',
        '4' => 'Quinta-Feira',
        '5' => 'Sexta-Feira',
        '6' => 'Sábado',
        '7' => 'Domingo'
    ];

    public function setInputName($value)
    {
        return $this->setName($value);
    }
}