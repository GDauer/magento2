<?php

namespace Itatiaia\CustomMercadoPago\Controller\Checkout;

use Exception;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\Action\Context;
use Magento\Checkout\Model\Session;
use Magento\Sales\Model\OrderFactory;
use Magento\Sales\Model\Order\Email\Sender\OrderSender;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Catalog\Model\Session as CatalogSession;
use MercadoPago\Core\Model\Core;
use MercadoPago\Core\Helper\ConfigData;
use MercadoPago\Core\Helper\Data;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Page
 * @package MercadoPago\Core\Controller\Checkout
 */
class Page
    extends \MercadoPago\Core\Controller\Checkout\Page
{
    /**
     * @return mixed
     */
    protected function _getOrder()
    {
        $order = $this->_checkoutSession->getLastRealOrder();
        return $order;
    }
}