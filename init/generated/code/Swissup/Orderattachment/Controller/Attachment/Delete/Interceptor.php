<?php
namespace Swissup\Orderattachment\Controller\Attachment\Delete;

/**
 * Interceptor class for @see \Swissup\Orderattachment\Controller\Attachment\Delete
 */
class Interceptor extends \Swissup\Orderattachment\Controller\Attachment\Delete implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\Controller\Result\RawFactory $resultRawFactory, \Swissup\Orderattachment\Helper\Attachment $attachmentHelper)
    {
        $this->___init();
        parent::__construct($context, $resultRawFactory, $attachmentHelper);
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
