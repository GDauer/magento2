<?php
namespace Potato\Compressor\Controller\Adminhtml\Cache\Clean;

/**
 * Interceptor class for @see \Potato\Compressor\Controller\Adminhtml\Cache\Clean
 */
class Interceptor extends \Potato\Compressor\Controller\Adminhtml\Cache\Clean implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\App\CacheInterface $appCache, \Potato\Compressor\Helper\Data $dataHelper, \Potato\Compressor\Helper\File $fileHelper)
    {
        $this->___init();
        parent::__construct($context, $appCache, $dataHelper, $fileHelper);
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
