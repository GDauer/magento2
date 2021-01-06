<?php
namespace DigitalHub\EstimateShipping\Controller\Estimate;

class Post extends \Magento\Framework\App\Action\Action
{
    public function __construct
    (
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Quote\Api\CartRepositoryInterface $cartRepositoryInterface,
        \Magento\Quote\Api\CartManagementInterface $cartManagementInterface,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Psr\Log\LoggerInterface $logger
    )
    {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->productFactory = $productFactory;
        $this->storeManager = $storeManager;
        $this->cartRepositoryInterface = $cartRepositoryInterface;
        $this->cartManagementInterface = $cartManagementInterface;
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
    }

    public function execute()
    {
        $result = $this->resultJsonFactory->create();
        $countryId = $this->scopeConfig->getValue('general/store_information/country_id');

        $postcode = $this->getRequest()->getPostValue('postcode');
        $qty = (int)$this->getRequest()->getPostValue('qty');
        $productId = (int)$this->getRequest()->getPostValue('productId');
        $superGroupItems = $this->getRequest()->getPostValue('superGroupItems');

        try {
            // create empty quote
            $quote = $this->_initQuote();

            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $scopeConfig = $objectManager->create('Intelipost\Quote\Model\Carrier\Intelipost');

            $productModel = $this->productFactory->create();
            $product = $productModel->load($productId);

            $this->logger->info("ESTIMATE_SHIPPING: ID: " .$product->getId() ." IS_SALABLE: " .$product->isSalable());
            $this->logger->info("active " .$scopeConfig->getConfigFlag ('active'));
            $this->logger->info("marketplace_enable " .$scopeConfig->getConfigFlag ('marketplace_enable'));
            $this->logger->info("marketplace_module_name " .$scopeConfig->getConfigData ('marketplace_module_name'));

            // simple and configurable items
            if(!$superGroupItems){
                $params = ['qty' => $qty ? $qty : 1];
                $dataObject = new \Magento\Framework\DataObject();
                $dataObject->setData($params);
                $quote->addProduct($product, $dataObject);
            } else {
                // grouped products
                $params = ['product' => $productId, 'super_group' => []];
                foreach($superGroupItems as $item){
                    $params['super_group'][$item['id']] = (int)$item['qty'];
                }
                $dataObject = new \Magento\Framework\DataObject();
                $dataObject->setData($params);
                $quote->addProduct($product, $dataObject);
            }

            $quote->collectTotals();

            // collect rates
            $rates = $this->_getShippingRates($quote, $postcode, $countryId);

            $result->setData([
                'success' => true,
                'items' => $rates
            ]);
        } catch (Exception $e){
            $result->setData([
                'error' => $e->getMessage()
            ]);
        }

        return $result;
    }

    private function _initQuote()
    {
        $store = $this->storeManager->getStore();
        $cartId = $this->cartManagementInterface->createEmptyCart(); //Create empty cart
        $quote = $this->cartRepositoryInterface->get($cartId); // load empty cart quote
		$quote->setIsActive(0);
        $quote->setStore($store);
        return $quote;
    }

    private function _getShippingRates($quote, $postcode, $countryId)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $priceHelper = $objectManager->create('Magento\Framework\Pricing\Helper\Data');
        $address = $objectManager->create('Magento\Customer\Model\Address');
        $address->setCountryId($countryId);
        $address->setPostcode($postcode);
		$addressData = $address->getData();

		//Set Address to quote
        $quote->getShippingAddress()->addData($addressData);

		$shippingAddress = $quote->getShippingAddress();
		$shippingAddress->setCollectShippingRates(true)
                        ->collectShippingRates();

		$ratesCollection = $quote->getShippingAddress()->getShippingRatesCollection();

		$rates = [];
		foreach($ratesCollection as $_rate){
            $rates[$_rate->getCode()] = [ // for duplication cleanup
				'title' => $_rate->getMethodTitle(),
                'code' => $_rate->getCode(),
                'price_formatted' => $priceHelper->currency(number_format($_rate->getPrice(),2),true,false),
                'price' => $_rate->getPrice(),
                'error_message' => $_rate->getErrorMessage()
			];
		}

        $cleanRates = [];
        foreach($rates as $rate){
            $cleanRates[] = $rate;
        }

        return $cleanRates;
    }
}
