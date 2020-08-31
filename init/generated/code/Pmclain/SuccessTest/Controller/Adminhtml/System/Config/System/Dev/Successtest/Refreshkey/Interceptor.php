<?php
namespace Pmclain\SuccessTest\Controller\Adminhtml\System\Config\System\Dev\Successtest\Refreshkey;

/**
 * Interceptor class for @see \Pmclain\SuccessTest\Controller\Adminhtml\System\Config\System\Dev\Successtest\Refreshkey
 */
class Interceptor extends \Pmclain\SuccessTest\Controller\Adminhtml\System\Config\System\Dev\Successtest\Refreshkey implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Controller\Result\JsonFactory $jsonFactory, \Magento\Config\Model\ResourceModel\Config $resourceConfig, \Magento\Framework\Math\Random $random, \Magento\Framework\App\Cache\TypeListInterface $cache)
    {
        $this->___init();
        parent::__construct($context, $jsonFactory, $resourceConfig, $random, $cache);
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
