<?php
namespace Itatiaia\ProductTag\Controller\Adminhtml\Tag\Save;

/**
 * Interceptor class for @see \Itatiaia\ProductTag\Controller\Adminhtml\Tag\Save
 */
class Interceptor extends \Itatiaia\ProductTag\Controller\Adminhtml\Tag\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory, \Magento\Framework\Message\ManagerInterface $messageManager, \Itatiaia\ProductTag\Model\TagFactory $tagFactory)
    {
        $this->___init();
        parent::__construct($context, $resultRedirectFactory, $messageManager, $tagFactory);
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
