<?php
namespace Swissup\CheckoutFields\Controller\Adminhtml\Order\Save;

/**
 * Interceptor class for @see \Swissup\CheckoutFields\Controller\Adminhtml\Order\Save
 */
class Interceptor extends \Swissup\CheckoutFields\Controller\Adminhtml\Order\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \Swissup\CheckoutFields\Model\Field\Validator $fieldsValidator, \Swissup\CheckoutFields\Model\FieldFactory $fieldFactory, \Swissup\CheckoutFields\Model\Field\ValueFactory $fieldValueFactory)
    {
        $this->___init();
        parent::__construct($context, $resultJsonFactory, $fieldsValidator, $fieldFactory, $fieldValueFactory);
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
