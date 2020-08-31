<?php
namespace Itatiaia\ProductTag\Controller\Adminhtml\Tag\Edit;

/**
 * Interceptor class for @see \Itatiaia\ProductTag\Controller\Adminhtml\Tag\Edit
 */
class Interceptor extends \Itatiaia\ProductTag\Controller\Adminhtml\Tag\Edit implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Registry $coreRegistry, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Itatiaia\ProductTag\Model\TagFactory $tagFactory)
    {
        $this->___init();
        parent::__construct($context, $coreRegistry, $resultPageFactory, $tagFactory);
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
