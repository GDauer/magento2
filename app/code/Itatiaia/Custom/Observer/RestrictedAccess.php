<?php
namespace Itatiaia\Custom\Observer;

class RestrictedAccess implements \Magento\Framework\Event\ObserverInterface
{

    private $_customHelper;
    // private $_coreSession;
    private $_cookieManager;
    private $_cookieMetadataFactory;
    private $_scopeConfig;
    private $_logger;

    public function __construct
    (
        \Itatiaia\Custom\Helper\Data $customHelper,
        // \Magento\Framework\Session\SessionManagerInterface $coreSession,
        \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager,
        \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Psr\Log\LoggerInterface $logger
    )
    {
        $this->_customHelper = $customHelper;
        // $this->_coreSession = $coreSession;
        $this->_cookieManager = $cookieManager;
        $this->_cookieMetadataFactory = $cookieMetadataFactory;
        $this->_scopeConfig = $scopeConfig;
        $this->_logger = $logger;
    }
    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        if((int)$this->_scopeConfig->getValue('dev/itatiaia_restricted_access/active', \Magento\Store\Model\ScopeInterface::SCOPE_STORE)){

            $allow = false;
            $allowedQuerystring = $this->_scopeConfig->getValue('dev/itatiaia_restricted_access/allow_querystring', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

            // verifica se possui QueryString para liberação via URL
            if(strpos($_SERVER['REQUEST_URI'], $allowedQuerystring) !== false){
                $this->_logger->info('ACESSO CONCEDIDO VIA LINK', [$_SERVER['REQUEST_URI']]);
                $this->_cookieManager->setPublicCookie(
                     'custom_restricted_access_allow',
                     '1',
                     $this->_cookieMetadataFactory->createPublicCookieMetadata()->setDuration(604800)->setPath('/')
                );
                $allow = true;
            }

            // verifica cookie já existente para liberação de acesso
            if($this->_cookieManager->getCookie('custom_restricted_access_allow')){
                $allow = true;
            }

            // habilita Adyen e REST
            if(strpos($_SERVER['REQUEST_URI'], '/adyen/') !== false || strpos($_SERVER['REQUEST_URI'], '/rest/') !== false){
                $allow = true;
            }

            // verificação por Basic Auth
            if(!$allow){
                $username = $this->_scopeConfig->getValue('dev/itatiaia_restricted_access/username', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
                $password = $this->_scopeConfig->getValue('dev/itatiaia_restricted_access/password', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

                if(isset($_SERVER['PHP_AUTH_USER']) && $_SERVER['PHP_AUTH_USER'] == $username){
                    if(isset($_SERVER['PHP_AUTH_PW']) && $_SERVER['PHP_AUTH_PW'] == $password){
                        $allow = true;
                    }
                }
            }

            // se não habilitado, envia header de acesso restrito
            if(!$allow){
                header('WWW-Authenticate: Basic realm="Acesso restrito"');
                header('HTTP/1.0 401 Unauthorized');
                $this->_logger->info('RESTRICTED', []);
                exit;
            }
        }
    }
}
