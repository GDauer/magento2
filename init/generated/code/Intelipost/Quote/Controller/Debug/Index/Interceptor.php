<?php
namespace Intelipost\Quote\Controller\Debug\Index;

/**
 * Interceptor class for @see \Intelipost\Quote\Controller\Debug\Index
 */
class Interceptor extends \Intelipost\Quote\Controller\Debug\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Intelipost\Quote\Helper\Data $intelipostHelper, \Intelipost\Quote\Model\QuoteFactory $quoteFactory)
    {
        $this->___init();
        parent::__construct($context, $intelipostHelper, $quoteFactory);
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
