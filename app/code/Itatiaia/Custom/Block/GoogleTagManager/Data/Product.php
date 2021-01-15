<?php

namespace Itatiaia\Custom\Block\GoogleTagManager\Data;

class Product extends \MagePal\GoogleTagManager\Block\Data\Product
{

  protected function _prepareLayout()
  {

      $tm = $this->getParentBlock();

      $categoryFactory = \Magento\Framework\App\ObjectManager::getInstance()->get('\Magento\Catalog\Model\CategoryFactory');
      $categories = [];

      $tm->addVariable(
          'list',
          'detail'
      );

      $product = $this->getProduct();

      if ($product)
      {
          foreach ($product->getCategoryIds() as $categoryId) {
              $categories = $categoryFactory->create()->load($categoryId)->getName();
          }

          if($product->getTypeId() == 'grouped'){
              $price = $tm->formatPrice(\Magento\Framework\App\ObjectManager::getInstance()->get('Itatiaia\GroupedCatalogRule\Helper\Data')->calculateGroupedFinalPrice($product));
          } else {
              $price = $tm->formatPrice($product->getPrice());
          }
          // exit;
          $tm->addVariable(
              'product',
              [
                  'id' => $product->getId(),
                  'sku' => $product->getSku(),
                  'name' => $product->getName(),
                  'category' => $categories,
                  'price' => $price,
                  'attribute_set_id' => $product->getAttributeSetId(),
                  'path' => implode(" > ", $this->getBreadCrumbPath())
              ]
          );
      }

      return $this;
  }

}
