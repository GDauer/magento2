<?php


namespace Itatiaia\Mooble\Controller\Cart;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Checkout\Model\CartFactory;
use Magento\Checkout\Model\Cart;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;

class Index extends Action
{
    /**
     * @var Session
     */
    protected $checkoutSession;

    protected $cartFactory;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var CartRepositoryInterface
     */
    protected $cartRepository;

    /**
     * @var FormKey
     */
    protected $formKey;

    /**
     * Index constructor.
     * @param Context $context
     * @param CartFactory $cartFactory
     * @param ProductRepositoryInterface $productRepository
     * @param LoggerInterface $logger
     * @param CartRepositoryInterface $cartRepository
     * @param FormKey $formKey
     */
    public function __construct(
        Context $context,
        CartFactory $cartFactory,
        ProductRepositoryInterface $productRepository,
        LoggerInterface $logger,
        CartRepositoryInterface $cartRepository,
        FormKey $formKey
    )
    {
        $this->cartFactory = $cartFactory;
        $this->productRepository = $productRepository;
        $this->logger = $logger;
        $this->cartRepository = $cartRepository;
        $this->formKey = $formKey;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|Redirect|ResultInterface
     */
    public function execute()
    {
        $skus = $this->getRequest()->getParam('sku');
        $moobleId = $this->getRequest()->getParam('mooblebudget');
        $skus = explode(',',$skus);
        $quote = $this->cartFactory->create();

        foreach ($skus as $sku) {
            if (!empty($sku)) {
                try {
                    $product = $this->productRepository->get($sku);
                    if($product->getTypeId() == 'grouped'){
                        $associatedProducts = $product->getTypeInstance(true)->getAssociatedProducts($product);
                        foreach ($associatedProducts as $item) {
                            $qty = $item->getQty() ? $item->getQty() : 1;
                            $quote = $this->addProduct($quote,$item, $qty);
                        }
                    } else {
                        $qty = 1;
                        $quote = $this->addProduct($quote, $product, $qty);
                    }
                } catch (NoSuchEntityException $e) {
                    $this->logger->warning(__('Product with sku %1 does not exists on the Mooble Controller', $sku),[$e->getMessage()]);
                }
            }
        }
        $quote->getQuote()->setMoobleId($moobleId);
        $quote->save();

        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('checkout/cart');
        return $resultRedirect;
    }

    /**
     * @param Cart $quote
     * @param Product $product
     * @param int $qty
     * @return Cart
     */
    protected function addProduct(Cart $quote, Product $product, int $qty) {
        if ($product->isSalable()) {
            try {
                $params = [
                    'form_key' => $this->formKey->getFormKey(),
                    'product' =>$product->getId(),
                    'price' => $product->getFinalPrice(),
                    'qty'   => $qty
                ];

                $quote->addProduct($product, $params);
            } catch (LocalizedException $e) {
                $this->logger->warning(__('Error adding the product with sku %1 to the cart on the Mooble Controller', $product->getSku()),[$e->getMessage()]);
                return $quote;
            }
        }
        return $quote;
    }
}