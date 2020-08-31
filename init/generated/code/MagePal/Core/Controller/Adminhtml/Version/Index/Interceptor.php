<?php
namespace MagePal\Core\Controller\Adminhtml\Version\Index;

/**
 * Interceptor class for @see \MagePal\Core\Controller\Adminhtml\Version\Index
 */
class Interceptor extends \MagePal\Core\Controller\Adminhtml\Version\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \MagePal\Core\Model\Module $module)
    {
        $this->___init();
        parent::__construct($context, $resultJsonFactory, $module);
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
