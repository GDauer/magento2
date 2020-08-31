<?php
namespace Swissup\Core\Controller\Adminhtml\Installer\Install;

/**
 * Interceptor class for @see \Swissup\Core\Controller\Adminhtml\Installer\Install
 */
class Interceptor extends \Swissup\Core\Controller\Adminhtml\Installer\Install implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Swissup\Core\Helper\PopupMessageManager $popupMessageManager)
    {
        $this->___init();
        parent::__construct($context, $popupMessageManager);
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
