<?php


namespace Itatiaia\Tracksale\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Order extends Template
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var Context
     */
    private $checkoutSession;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        Template\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->scopeConfig = $scopeConfig;
        $this->checkoutSession = $checkoutSession;
    }

    public function getOrder()
    {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $this->checkoutSession->getLastRealOrder();
        return $order;
    }

    public function getConfigPath($value)
    {
        return $this->scopeConfig->getValue('sales/itatiaia_tracksale/'.$value,\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
}