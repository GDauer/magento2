<?php
namespace DigitalHub\FeedManager\Controller\Adminhtml\Feed\Generate;

/**
 * Interceptor class for @see \DigitalHub\FeedManager\Controller\Adminhtml\Feed\Generate
 */
class Interceptor extends \DigitalHub\FeedManager\Controller\Adminhtml\Feed\Generate implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Registry $coreRegistry, \DigitalHub\FeedManager\Helper\Feed $feedHelper, \DigitalHub\FeedManager\Model\FeedFactory $feedFactory, \DigitalHub\FeedManager\Model\Feed\TemplateFactory $feedTemplateFactory, \Magento\Framework\View\Result\PageFactory $resultPageFactory)
    {
        $this->___init();
        parent::__construct($context, $coreRegistry, $feedHelper, $feedFactory, $feedTemplateFactory, $resultPageFactory);
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
