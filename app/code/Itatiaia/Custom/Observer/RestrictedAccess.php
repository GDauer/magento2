<?php
namespace Itatiaia\Custom\Observer;

class RestrictedAccess implements \Magento\Framework\Event\ObserverInterface
{

    private $_customHelper;
    private $_customerSession;
    private $_logger;

    public function __construct
    (
        \Itatiaia\Custom\Helper\Data $customHelper,
        \Magento\Customer\Model\Session $customerSession,
        \Psr\Log\LoggerInterface $logger
    )
    {
        $this->_customHelper = $customHelper;
        $this->_customerSession = $customerSession;
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
        if(strpos($_SERVER['REQUEST_URI'], 'restricted=0') !== false){
            $this->_logger->info('ACESSO CONCEDIDO VIA LINK', [$_SERVER['REQUEST_URI']]);
            $this->_customerSession->setRestrictedCustomerAllowed(true);
        }

        if(!$this->_customerSession->getRestrictedCustomerAllowed()){
            // if (!isset($_SERVER['PHP_AUTH_USER'])) {
                header('WWW-Authenticate: Basic realm="Acesso restrito"');
                header('HTTP/1.0 401 Unauthorized');
                $this->_logger->info('RESTRICTED', []);
                exit;
            // }
        } else {
            $this->_logger->info('RESTRICTED PASS', []);
        }
    }
}
