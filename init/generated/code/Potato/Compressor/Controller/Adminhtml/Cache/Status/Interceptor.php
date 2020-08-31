<?php
namespace Potato\Compressor\Controller\Adminhtml\Cache\Status;

/**
 * Interceptor class for @see \Potato\Compressor\Controller\Adminhtml\Cache\Status
 */
class Interceptor extends \Potato\Compressor\Controller\Adminhtml\Cache\Status implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Potato\Compressor\Block\Adminhtml\System\Config\Cache\StatusFactory $statusFactory)
    {
        $this->___init();
        parent::__construct($context, $statusFactory);
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
