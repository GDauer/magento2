<?php


namespace Itatiaia\GarantiaEstendida\Block;


use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Template;

class Garantia extends Template
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Template\Context $context,
        array $data = [])
    {
        parent::__construct($context, $data);
        $this->scopeConfig = $scopeConfig;
    }

    public function isActive(){
        return $this->scopeConfig->getValue('sales/itatiaia_garantiaestendida/active', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getMessage(){
        return $this->scopeConfig->getValue('sales/itatiaia_garantiaestendida/message', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
}