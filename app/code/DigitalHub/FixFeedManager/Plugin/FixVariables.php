<?php


namespace DigitalHub\FixFeedManager\Plugin;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
/**
 * This plugin replaces the feed variable with the original image path instead of the cached images.
 * Class FixVariables
 * @package DigitalHub\FixFeedManager\Plugin
 */
class FixVariables
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * FixVariables constructor.
     * @param ProductRepositoryInterface $productRepository
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        StoreManagerInterface $storeManager
    )
    {
        $this->productRepository = $productRepository;
        $this->storeManager = $storeManager;
    }

    /**
     * @param \DigitalHub\FeedManager\Helper\Feed $subject
     * @param array $result
     * @param Collection $collection
     * @return array
     */
    public function afterParseTemplateVariables(\DigitalHub\FeedManager\Helper\Feed $subject, $result, $collection)
    {
        if (count($result['products']) > 0) {
            $currentStore = $this->storeManager->getStore();
            $currencyCode = $currentStore->getCurrentCurrency()->getCode();
            $baseUrl = $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
            foreach ($result['products'] as $id => $product) {
                if (isset($product['entity_id'])) {
                    $prod = $this->productRepository->getById($product['entity_id']);
                    $result['products'][$id]['final_price_with_currency_code'] = $prod->getFinalPrice() . ' ' . $currencyCode;
                    if (!empty($prod->getImage())) {
                        $result['products'][$id]['image'] = $baseUrl . 'catalog/product' . $prod->getImage();
                    }
                    if (!empty($prod->getSmallImage())) {
                        $result['products'][$id]['small_image'] = $baseUrl . 'catalog/product' . $prod->getSmallImage();
                    }
                    if (!empty($prod->getThumbnail())) {
                        $result['products'][$id]['thumbnail'] = $baseUrl . 'catalog/product' . $prod->getThumbnail();
                    }
                }
            }
        }
        return $result;
    }
}