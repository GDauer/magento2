<?php
namespace Itatiaia\Tracksale\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    private $logger;
    private $httpClient;
    protected $_scopeConfig;

    public function __construct
    (
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Zend\Http\Client $httpClient,
        \Psr\Log\LoggerInterface $logger
    )
    {
        $this->logger = $logger;
        $this->httpClient = $httpClient;
        $this->_scopeConfig = $scopeConfig;
    }

    public function sendCustomer($customerName, $customerEmail, $customerTelephone = null)
    {
        $isActive = (int)$this->_scopeConfig->getValue('sales/itatiaia_tracksale/active', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $token = $this->_scopeConfig->getValue('sales/itatiaia_tracksale/token', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $campaign = $this->_scopeConfig->getValue('sales/itatiaia_tracksale/campaign', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->logger->info('TOKEN: ', [$token]);
        if($isActive){
            if($token){
                try {
                    $post = [
                        "customers" => [
                            [
                                "name" => $customerName,
                                "email" => $customerEmail,
                                "phone" => $customerTelephone
                            ]
                        ],
                        "schedule_time" => 0
                    ];

                    $uri = 'https://api.tracksale.co/v2/campaign/'.$campaign.'/dispatch';
                    $this->httpClient->reset();
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
}
