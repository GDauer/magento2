<?php
namespace Itatiaia\Custom\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    private $_productFactory;

    public function __construct
    (
        \Magento\Catalog\Model\ProductFactory $productFactory
    )
    {
        $this->_productFactory = $productFactory;
    }

    public function verifyIsGroupedLockedItems($quoteItem)
    {
        $logger = \Magento\Framework\App\ObjectManager::getInstance()->get('\Psr\Log\LoggerInterface');

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
