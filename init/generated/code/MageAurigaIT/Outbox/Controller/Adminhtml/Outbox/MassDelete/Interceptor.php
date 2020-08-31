<?php
namespace MageAurigaIT\Outbox\Controller\Adminhtml\Outbox\MassDelete;

/**
 * Interceptor class for @see \MageAurigaIT\Outbox\Controller\Adminhtml\Outbox\MassDelete
 */
class Interceptor extends \MageAurigaIT\Outbox\Controller\Adminhtml\Outbox\MassDelete implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Ui\Component\MassAction\Filter $filter, \MageAurigaIT\Outbox\Model\ResourceModel\SentEmail\CollectionFactory $collectionFactory)
    {
        $this->___init();
        parent::__construct($context, $filter, $collectionFactory);
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
