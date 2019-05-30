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

    function getGroupedByOrderItem($item)
    {
        $isGroupedItem = false;
        $groupedProductId = false;
        foreach($item->getBuyRequest()->getData() as $option){
            if(isset($option['product_type']) && $option['product_type'] == 'grouped'){
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

    function getGroupedCartQty($product, $quote)
    {
        $associatedProducts = $product->getTypeInstance(true)->getAssociatedProductIds($product);
        $quoteItems = $quote->getAllVisibleItems();
        $quoteItemsQty = [];
        foreach ($quoteItems as $item)
        {
            $quoteProductId = $item->getProduct()->getId();
            if(in_array($quoteProductId, $associatedProducts))
            {
                $quoteItemsQty[] = $item->getQty();
            }
        }

        return min($quoteItemsQty);
    }

    function getGroupedSuccessQty($product, $order)
    {
        $associatedProducts = $product->getTypeInstance(true)->getAssociatedProductIds($product);
        $orderItems = $order->getAllVisibleItems();
        $orderItemsQty = [];
        foreach ($orderItems as $item)
        {
            $quoteProductId = $item->getProductId();
            if(in_array($quoteProductId, $associatedProducts))
            {
                $orderItemsQty[] = $item->getQtyOrdered();
            }
        }

        return min($orderItemsQty);
    }

    public function removeDuplicates($array, $keep_key_assoc = false){
      $duplicate_keys = array();
      $tmp = array();

      foreach ($array as $key => $val){
          // convert objects to arrays, in_array() does not support objects
          if (is_object($val))
              $val = (array)$val;

          if (!in_array($val, $tmp))
              $tmp[] = $val;
          else
              $duplicate_keys[] = $key;
      }

      foreach ($duplicate_keys as $key)
          unset($array[$key]);

      return $keep_key_assoc ? $array : array_values($array);
    }

    public function getStoreId()
    {
        $storeManager = \Magento\Framework\App\ObjectManager::getInstance()->get('\Magento\Store\Model\StoreManagerInterface');
        return $storeManager->getStore()->getId();
    }

    public function getCheckoutCartItems()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $cart = $objectManager->get('\Magento\Checkout\Model\Cart');
        return $cart->getQuote()->getAllVisibleItems();
    }

    public function getGroupedPriceInCart($groupedProduct, $quoteItems)
    {
        // calcula o preço final do produto agrupado no carrinho, somando os valores dos produtos associados adicionados no carrinho
        $finalPrice = 0;

        foreach($quoteItems as $item){
            if($groupedItem = $this->getGroupedByQuoteItem($item)){
                if($groupedItem->getId() == $groupedProduct->getId()){
                    $finalPrice+= $item->getPrice() * $item->getQty();
                }
            }
        }

        return $finalPrice;
    }
}
