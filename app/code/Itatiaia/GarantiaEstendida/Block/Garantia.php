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
    /**
     * @var Itatiaia\Custom\Helper
     */
    private $helper;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Template\Context $context,
        \Itatiaia\Custom\Helper\Data $helper,
        array $data = [])
    {
        parent::__construct($context, $data);
        $this->scopeConfig = $scopeConfig;
        $this->helper = $helper;
    }

    public function canShowOnCheckout($quote)
    {
        foreach ($quote->getAllVisibleItems() as $item)
        {
            if($grouped = $this->helper->getGroupedByQuoteItem($item)){
                if(!$this->canShowGarantia($grouped)) {
                    continue;
                }
                return true;
            } else {
                if(!$this->canShowGarantia($item)){
                    continue;
                }
                return true;
            }
        }
        return false;
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