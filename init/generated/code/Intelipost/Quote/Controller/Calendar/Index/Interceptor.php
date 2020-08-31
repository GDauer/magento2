<?php
namespace Intelipost\Quote\Controller\Calendar\Index;

/**
 * Interceptor class for @see \Intelipost\Quote\Controller\Calendar\Index
 */
class Interceptor extends \Intelipost\Quote\Controller\Calendar\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Intelipost\Quote\Helper\Data $helper, \Intelipost\Quote\Model\QuoteFactory $quoteFactory)
    {
        $this->___init();
        parent::__construct($context, $resultPageFactory, $helper, $quoteFactory);
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
