<?php


namespace DigitalHub\FixUpdateCart\Plugin;

use Magento\Checkout\Model\Cart;
use Magento\GroupedProduct\Model\ResourceModel\Product\Link;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductFactory;

/**
 * This plugin verify if there is a grouped product on the cart with attribute lock_items with value true e then corrects its children products quantity in the cart
 * Class UpdateGroupedProductQty
 * @package DigitalHub\FixUpdateCart\Plugin
 */
class UpdateGroupedProductQty
{
    /**
     * @var Cart
     */
    private $cart;
    /**
     * @var Link
     */
    private $catalogProductLink;
    /**
     * @var ProductFactory
     */
    private $productFactory;

    /**
     * UpdateGroupedProductQty constructor.
     * @param Cart $cart
     * @param Link $catalogProductLink
     * @param ProductFactory $productFactory
     */
    public function __construct(
        Cart $cart,
        Link $catalogProductLink,
        ProductFactory $productFactory
    )
    {
        $this->cart = $cart;
        $this->catalogProductLink = $catalogProductLink;
        $this->productFactory = $productFactory;
    }

    /**
     * After execute
     * @param \Magento\Checkout\Controller\Cart\UpdateItemQty $subject
     * @param $result
     * @return mixed
     */
    public function afterExecute(\Magento\Checkout\Controller\Cart\UpdateItemQty $subject, $result)
    {
        $cartData = $subject->getRequest()->getParam('cart');
        foreach ($cartData as $key => $value) {
            $childProduct = $this->cart->getQuote()->getItemById($key)->getProduct();
            $items = $this->cart->getQuote()->getAllVisibleItems();
            $parentsIds = $this->catalogProductLink->getParentIdsByChild(
                $childProduct->getId(),
                \Magento\GroupedProduct\Model\ResourceModel\Product\Link::LINK_TYPE_GROUPED
            );
            $groupedIds = [];
            foreach ($parentsIds as $id) {
                $products = $this->productFactory->create();
                $products->addFieldToSelect('*')->addAttributeToFilter('entity_id', $id);
                if ($products->getSize() > 0) {
                    $groupedProduct = $products->getFirstItem();
                    if ($groupedProduct->getTypeId() === 'grouped' && $groupedProduct->getLockItems()) {
                        $groupedIds[] = $id;
                    }
                }
            }

            foreach ($items as $item) {
                if ($key != $item->getId()) {
                    $product = $item->getProduct();
                    $parentsIds = $this->catalogProductLink->getParentIdsByChild(
                        $product->getId(),
                        \Magento\GroupedProduct\Model\ResourceModel\Product\Link::LINK_TYPE_GROUPED
                    );
                    foreach ($parentsIds as $id) {
                        if (array_search($id, $groupedIds) !== false) {
                            $params[$item->getId()]['qty'] = $value['qty'];
                            break;
                        }
                    }
                }
            }

            $this->cart->updateItems($params);
            $this->cart->saveQuote();
            $this->cart->save();
        }

        return $result;
    }
}