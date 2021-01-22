<?php


namespace Itatiaia\Custom\Controller\Checkout\Onepage;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Checkout\Model\Session;
use Psr\Log\LoggerInterface;
use Magento\Sales\Model\OrderFactory;

class Success extends \Magento\Checkout\Controller\Onepage
{
    /**
     * @var Session
     */
    protected $checkoutSession;
    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var OrderFactory
     */
    protected $orderFactory;
    /**
     * @var string
     */
    protected $lastOrderId;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param CustomerRepositoryInterface $customerRepository
     * @param AccountManagementInterface $accountManagement
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\Translate\InlineInterface $translateInline
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\View\LayoutFactory $layoutFactory
     * @param \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param Session $checkoutSession
     * @param LoggerInterface $logger
     * @param OrderFactory $orderFactory
     *
     * @codeCoverageIgnore
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        CustomerRepositoryInterface $customerRepository,
        AccountManagementInterface $accountManagement,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\Translate\InlineInterface $translateInline,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        Session $checkoutSession,
        LoggerInterface $logger,
        OrderFactory $orderFactory
    ) {
        parent::__construct(
            $context,
            $customerSession,
            $customerRepository,
            $accountManagement,
            $coreRegistry,
            $translateInline,
            $formKeyValidator,
            $scopeConfig,
            $layoutFactory,
            $quoteRepository,
            $resultPageFactory,
            $resultLayoutFactory,
            $resultRawFactory,
            $resultJsonFactory
        );

        $this->checkoutSession = $checkoutSession;
        $this->logger = $logger;
        $this->orderFactory = $orderFactory;
    }

    public function execute()
    {
        $session = $this->getOnepage()->getCheckout();
        error_log(print_r($session->getData()), 3, "/tmp/my-errors.log");
        if (!$this->isValid()) {
            return $this->resultRedirectFactory->create()->setPath('checkout/cart');
        }
        $session->clearQuote();
        //@todo: Refactor it to match CQRS
        $resultPage = $this->resultPageFactory->create();
        $this->_eventManager->dispatch(
            'checkout_onepage_controller_success_action',
            ['order_ids' => [(string)$this->lastOrderId]]
        );
        return $resultPage;
    }

    public function isValid()
    {
        $quoteId = $this->checkoutSession->getLastSuccessQuoteId();
        $lastQuoteId = (string)$this->checkoutSession->getLastQuoteId();

        $this->lastOrderId = $this->checkoutSession->getLastOrderId();
        $this->logger->info('GETTING NATIVE ORDER ID', ['lastOrderId' => $this->lastOrderId]);
        if (!$this->lastOrderId) {
            $this->lastOrderId = $this->checkoutSession->getCustomLastOrderId();
            $this->logger->info('GETTING CUSTOM ORDER ID', ['lastOrderId' => $this->lastOrderId]);
        }

        try {
            $order = $this->orderFactory->create()->load($this->lastOrderId);
            $this->logger->info('ORDER SUCCESS', ['increment_id' => $order->getIncrementId(), 'quoteId' => $quoteId, 'lastOrderId' => $this->lastOrderId, 'lastQuoteId' => $lastQuoteId]);
        } catch (\Exception $e){
            $this->logger->error('Pedido gerado com erro.', [$e->getMessage()]);
        }

        if((!$this->lastOrderId))
        {
            return false;
        }
        return true;
    }
}
