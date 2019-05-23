<?php


namespace Itatiaia\OpenGraph\Block;


use Magento\Framework\View\Element\Template;

class OpenGraph extends \Magento\Framework\View\Element\Template
{
    protected $storeManager;
    protected $scopeConfig;
    protected $logo;

    public function __construct
    (
        Template\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Theme\Block\Html\Header\Logo $logo

    ){
        parent::__construct($context);
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->logo = $logo;
    }

    public function getStoreUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl();
    }
    public function getStoreName()
    {
        return $this->scopeConfig->getValue('general/store_information/name', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
    public function getLogoSrc()
    {
        return $this->logo->getLogoSrc();
    }
    public function getMetaDescription()
    {
        return $this->scopeConfig->getValue('design/head/default_description', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
    public function isHomePage()
    {
        return $this->logo->isHomePage();
    }
}

