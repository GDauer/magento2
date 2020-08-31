<?php
namespace Itatiaia\FixIntelipost\Controller\Adminhtml\Orders\Resync;

/**
 * Interceptor class for @see \Itatiaia\FixIntelipost\Controller\Adminhtml\Orders\Resync
 */
class Interceptor extends \Itatiaia\FixIntelipost\Controller\Adminhtml\Orders\Resync implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Controller\ResultFactory $resultFactory)
    {
        $this->___init();
        parent::__construct($context, $resultFactory);
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
