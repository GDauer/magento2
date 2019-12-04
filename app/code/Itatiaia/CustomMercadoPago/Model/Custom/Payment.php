<?php

namespace Itatiaia\CustomMercadoPago\Model\Custom;

class Payment extends \MercadoPago\Core\Model\Custom\Payment
{

    /**
     * @var \Itatiaia\CustomMercadoPago\Http\Order\Payment
     */
    private $http;

    public function __construct(
        \MercadoPago\Core\Helper\Data $helperData,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory,
        \Magento\Payment\Helper\Data $paymentData,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Payment\Model\Method\Logger $logger,
        \Magento\Framework\Module\ModuleListInterface $moduleList,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \MercadoPago\Core\Model\Core $coreModel,
        \Magento\Framework\App\RequestInterface $request,
        \Itatiaia\CustomMercadoPago\Http\Order\Payment $http
    )
    {
        parent::__construct($helperData, $checkoutSession, $customerSession, $orderFactory, $urlBuilder, $context, $registry, $extensionFactory, $customAttributeFactory, $paymentData, $scopeConfig, $logger, $moduleList, $localeDate, $coreModel, $request);
        $this->http = $http;
    }

    /**
     * Check refund availability
     *
     * @return bool
     * @api
     * @deprecated 100.2.0
     */
    public function canRefund()
    {
        return $this->_canRefund;
    }

    public function refund(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        if (!$this->canRefund()) {
            throw new \Magento\Framework\Exception\LocalizedException(__('The refund action is not available.'));
        }
        $response = $this->http->refund($payment);
        if($response['status'] > 201) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Não foi possível fazer o estorno no Mercado Pago.'));
        }
        return $this;
    }
}