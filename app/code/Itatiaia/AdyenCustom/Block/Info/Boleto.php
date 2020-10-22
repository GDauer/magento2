<?php
namespace Itatiaia\AdyenCustom\Block\Info;

class Boleto extends \Adyen\Payment\Block\Info\Boleto
{
    /**
     * @var string
     */
    protected $_template = 'Itatiaia_AdyenCustom::info/adyen_boleto_custom.phtml';
}
