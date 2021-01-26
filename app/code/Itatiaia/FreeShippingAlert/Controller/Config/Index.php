<?php
namespace Itatiaia\FreeShippingAlert\Controller\Config;

class Index extends \Magento\Framework\App\Action\Action
{
    public function __construct
    (
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Psr\Log\LoggerInterface $logger
    )
    {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->checkoutSession = $checkoutSession;
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
    }

    public function execute()
    {
        $result = $this->resultJsonFactory->create();
        $active = $this->scopeConfig->getValue('carriers/itatiaia_freeshippingalert/active');
        $subtotal = $this->scopeConfig->getValue('carriers/itatiaia_freeshippingalert/subtotal');
        $quote = $this->checkoutSession->getQuote();

        $result->setData([
            'active' => $active,
            'trigger_subtotal' => $subtotal,
            'cart_subtotal' => $quote->getSubtotal()
        ]);

        return $result;
    }
}
