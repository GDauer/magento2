<?php
namespace Itatiaia\Custom\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    private $_productFactory;
    private $_scopeConfig;

    public function __construct
    (
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        $this->_scopeConfig = $scopeConfig;
        $this->_productFactory = $productFactory;
    }

    public function getInstallmentsConfig(){
        $serializedConfig = $this->_scopeConfig->getValue('payment/adyen_cc/installments');
        $configData = unserialize($serializedConfig);
        return $configData;
    }

    public function verifyIsGroupedLockedItems($quoteItem)
    {
        if($parentProduct = $this->getGroupedByQuoteItem($quoteItem)){
            if($parentProduct->getLockItems()){
                return true;
            }
        }
        return false;
    }

    function getGroupedByQuoteItem($quoteItem)
    {
        $isGroupedItem = false;
        $groupedProductId = false;
        foreach($quoteItem->getOptions() as $option){
            if($option['code'] == 'product_type' && $option['value'] == 'grouped'){
                $groupedProductId = $option['product_id'];
                $isGroupedItem = true;
                break;
            }
        }

        if($isGroupedItem){
            $parentProduct = $this->_productFactory->create();
            return $parentProduct->load($groupedProductId);
        }

        return false;
    }
}
