<?php
namespace Itatiaia\AllinIntegration\Helper;

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

    public function generateToken()
    {
        $username = $this->_scopeConfig->getValue('newsletter/itatiaia_allinintegration/username', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $password = $this->_scopeConfig->getValue('newsletter/itatiaia_allinintegration/password', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $token = null;

        try {
            $this->httpClient->reset();
            $this->httpClient->setUri('https://painel02.allinmail.com.br/allinapi/?method=get_token&output=json&username='.$username.'&password=' . $password);
            $response = $this->httpClient->send();

            $result = json_decode($response->getBody());
            $token = $result->token;
        } catch (\Exception $e){
            $this->logger->error('Não foi possível resgatar token Allin.', [$e->getMessage()]);
        }

        return $token;
    }

    public function sendCustomer($customerEmail, $customerName = '')
    {
        $isActive = (int)$this->_scopeConfig->getValue('newsletter/itatiaia_allinintegration/active', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        if($isActive){
            $lista = $this->_scopeConfig->getValue('newsletter/itatiaia_allinintegration/lista', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $token = $this->generateToken();

            if($token){
                try {
                    $post = [
                        'dados_email' => '{"nm_lista":"'.$lista.'","campos":"nm_email;nome","valor":"'.$customerEmail.';'.$customerName.'"}'
                    ];

                    $uri = 'https://painel02.allinmail.com.br/allinapi/?method=inserir_email_base&output=json&token=' . $token;
                    $this->httpClient->reset();
                    $this->httpClient->setUri($uri);
                    $this->httpClient->setMethod(\Zend\Http\Request::METHOD_POST);
                    $this->httpClient->setParameterPost($post);
                    $response = $this->httpClient->send();
                    // $this->logger->info('Token', [$token]);
                    // $this->logger->info('POST', [$post]);
                    // $this->logger->info('Resposta Allin', [$response->getBody()]);
                } catch (\Exception $e){
                    $this->logger->info('Erro ao enviar cliente para AllIn', [$e->getMessage()]);
                }
            }
        }
    }
}
