<?php
namespace DigitalHub\FeedManager\Controller\Adminhtml\Feed\Save;

/**
 * Interceptor class for @see \DigitalHub\FeedManager\Controller\Adminhtml\Feed\Save
 */
class Interceptor extends \DigitalHub\FeedManager\Controller\Adminhtml\Feed\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Controller\ResultFactory $resultFactory, \DigitalHub\FeedManager\Model\FeedFactory $feedFactory)
    {
        $this->___init();
        parent::__construct($context, $resultFactory, $feedFactory);
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
