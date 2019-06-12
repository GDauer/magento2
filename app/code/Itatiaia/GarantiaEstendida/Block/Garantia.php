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

    public function canShowGarantia($product)
    {
        $categoryIds = $product->getCategoryIds();
        $categoriesAllowed = $this->getCategories();

        if(!is_array($categoriesAllowed)){
            $categoriesAllowed = [$categoriesAllowed];
        }
        if(!is_array($categoryIds)){
            $categoryIds = [$categoryIds];
        }

        if(!array_intersect($categoryIds, $categoriesAllowed)) {
            return false;
        }
        return true;
    }

    public function isActive(){
        return $this->scopeConfig->getValue('sales/itatiaia_garantiaestendida/active', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getMessage(){
        return $this->scopeConfig->getValue('sales/itatiaia_garantiaestendida/message', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
    public function getCategories(){
        return $this->scopeConfig->getValue('sales/itatiaia_garantiaestendida/categorias', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
}