<?php
namespace Intelipost\Quote\Controller\Schedule\Status;

/**
 * Interceptor class for @see \Intelipost\Quote\Controller\Schedule\Status
 */
class Interceptor extends \Intelipost\Quote\Controller\Schedule\Status implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Intelipost\Quote\Model\QuoteFactory $quoteFactory, \Intelipost\Quote\Helper\Data $quoteHelper, \Magento\Checkout\Model\Session $checkouSession, \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager)
    {
        $this->___init();
        parent::__construct($context, $resultPageFactory, $quoteFactory, $quoteHelper, $checkouSession, $cookieManager);
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'dispatch');
        if (!$pluginInfo) {
            return parent::dispatch($request);
        } else {
            return $this->___callPlugins('dispatch', func_get_args(), $pluginInfo);
        }
    }
}
