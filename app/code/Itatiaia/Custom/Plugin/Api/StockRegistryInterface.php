<?php


namespace Itatiaia\Custom\Plugin\Api;


use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Registry;
use Magento\Catalog\Model\ProductFactory;

class StockRegistryInterface
{

    /**
     * @var Registry
     */
    private $registry;
    /**
     * @var RequestInterface
     */
    private $request;
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    public function __construct
    (
        Registry $registry,
        RequestInterface $request,
        ProductRepositoryInterface $productRepository
    )
    {
        $this->registry = $registry;
        $this->request = $request;
        $this->productRepository = $productRepository;
    }

    public function afterUpdateStockItemBySku(
        \Magento\CatalogInventory\Api\StockRegistryInterface $subject,
        $itemId,
        $productSku
    )
    {
        if(!$this->request->getModuleName()){
            if($this->registry->registry('update_product_after_stock_sku') == $productSku){
                return $itemId;
            }
            $product = $this->productRepository->get($productSku);

            if($product->getTypeId() === 'simple') {
                $this->registry->unregister('update_product_after_stock_sku');
                $this->registry->register('update_product_after_stock_sku', $productSku);
                $product->save();
            }
        }

        return $itemId;
    }
}