<?php
namespace MundiPagg\MundiPagg\Controller\Adminhtml\Charges\Cancel;

/**
 * Interceptor class for @see \MundiPagg\MundiPagg\Controller\Adminhtml\Charges\Cancel
 */
class Interceptor extends \MundiPagg\MundiPagg\Controller\Adminhtml\Charges\Cancel implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \Magento\Framework\App\Request\Http $request)
    {
        $this->___init();
        parent::__construct($context, $resultJsonFactory, $request);
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
