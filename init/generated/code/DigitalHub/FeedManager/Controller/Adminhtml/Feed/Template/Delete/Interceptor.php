<?php
namespace DigitalHub\FeedManager\Controller\Adminhtml\Feed\Template\Delete;

/**
 * Interceptor class for @see \DigitalHub\FeedManager\Controller\Adminhtml\Feed\Template\Delete
 */
class Interceptor extends \DigitalHub\FeedManager\Controller\Adminhtml\Feed\Template\Delete implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Controller\ResultFactory $resultFactory, \DigitalHub\FeedManager\Model\Feed\TemplateFactory $feedTemplateFactory)
    {
        $this->___init();
        parent::__construct($context, $resultFactory, $feedTemplateFactory);
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
