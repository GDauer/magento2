<?php
namespace Itatiaia\Tracksale\Helper;

use Magento\Directory\Model\RegionFactory;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    private $logger;
    private $httpClient;
    protected $_scopeConfig;
    /**
     * @var RegionFactory
     */
    private $regionFactory;

    public function __construct
    (
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Zend\Http\Client $httpClient,
        \Psr\Log\LoggerInterface $logger,
        RegionFactory $regionFactory
    )
    {
        $this->logger = $logger;
        $this->httpClient = $httpClient;
        $this->_scopeConfig = $scopeConfig;
        $this->regionFactory = $regionFactory;
    }

    public function sendCustomer($order)
    {

        $isActive = (int)$this->_scopeConfig->getValue('sales/itatiaia_tracksale/active', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $token = $this->_scopeConfig->getValue('sales/itatiaia_tracksale/token', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $campaignId = $this->_scopeConfig->getValue('sales/itatiaia_tracksale/campaign_id', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->logger->info('TOKEN: ', [$token]);


        if($isActive){
            if($token){

                $customerEmail = $order->getCustomerEmail();
                $customerName = $order->getBillingAddress()->getFirstname() . ' ' . $order->getBillingAddress()->getLastname();
                $customerPhone = $order->getBillingAddress()->getTelephone();

                try {
                    $post = [
                        "customers" => [
                            [
                                "name" => $customerName,
                                "email" => $customerEmail,
                                "phone" => $customerPhone,
                                "tags" => [
                                    [
                                        'name' => "TagMg_Pedido",
                                        'value' => $order->getIncrementId()
                                    ],
                                    [
                                        'name' => "TagMg_DataCompra",
                                        'value' => $order->getCreatedAt()
                                    ],
                                    [
                                        'name' => "TagMg_UF",
                                        'value' => $this->getRegionUf($order->getShippingAddress()->getRegion())
                                    ],
                                    [
                                        'name' => "TagMg_Produto",
                                        'value' => $this->getProductSkus($order)
                                    ]
                                ]
                            ]
                        ],
                        "schedule_time" => 0
                    ];

                    $this->logger->info('post: ', $post);

                    $uri = 'https://api.tracksale.co/v2/campaign/'.$campaignId.'/dispatch';
                    $this->httpClient->reset();
                    $this->httpClient->setHeaders(
                        [
                            'Authorization' => 'bearer '.$token,
                            'Content-Type' => 'application/json'
                        ]
                    );
                    $this->httpClient->setUri($uri);
                    $this->httpClient->setMethod(\Zend\Http\Request::METHOD_POST);
                    $this->httpClient->setRawBody(json_encode($post));
                    $response = $this->httpClient->send();

                    $this->logger->info('Resposta Tracksale', [$response->getBody()]);
                } catch (\Exception $e){
                    $this->logger->info('Erro ao enviar cliente para Tracksale', [$e->getMessage()]);
                }
            }
        }
    }

    public function getRegionUf($regionName){
        $region = $this->regionFactory->create()->loadByName($regionName, 'BR');
        return $region->getCode();
    }

    public function getProductSkus($order)
    {
        $objectManager = \Magento\Framework\App\objectManager::getInstance();
        $productRepository = $objectManager->create('\Magento\Catalog\Model\ProductRepository');

        $skus = [];
        foreach ($order->getAllVisibleItems() as $item) {
            $productOptions = $item->getProductOptions();
            if ($productOptions) {
                if (isset($productOptions['info_buyRequest'])) {
                    $info_buyRequest = $productOptions['info_buyRequest'];
                    if (isset($info_buyRequest['super_product_config'])) {
                        $productId = $info_buyRequest['super_product_config']['product_id'];
                        if ($productId) {
                            $parentProduct = $productRepository->getById($productId);
                            if ($parentProduct) {
                                $sku = $parentProduct->getSku();
                                if (!in_array($sku, $skus)) {
                                    $skus[] = $sku;
                                }
                                continue;
                            }
                        }
                    }
                }
            }

            $skus[] = $item->getSku();
        }

        return implode(',', $skus);
    }
}
