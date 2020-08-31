<?php
namespace MageAurigaIT\Outbox\Controller\Adminhtml\Outbox\Resend;

/**
 * Interceptor class for @see \MageAurigaIT\Outbox\Controller\Adminhtml\Outbox\Resend
 */
class Interceptor extends \MageAurigaIT\Outbox\Controller\Adminhtml\Outbox\Resend implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Request\Http $request, \Magento\Framework\Mail\TransportInterfaceFactory $transportFactory, \MageAurigaIT\Outbox\Model\SentEmailFactory $sentEmailFactory, \Magento\Backend\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory)
    {
        $this->___init();
        parent::__construct($request, $transportFactory, $sentEmailFactory, $context, $resultPageFactory);
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
