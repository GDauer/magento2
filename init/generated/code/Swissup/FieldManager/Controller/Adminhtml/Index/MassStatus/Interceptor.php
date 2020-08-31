<?php
namespace Swissup\FieldManager\Controller\Adminhtml\Index\MassStatus;

/**
 * Interceptor class for @see \Swissup\FieldManager\Controller\Adminhtml\Index\MassStatus
 */
class Interceptor extends \Swissup\FieldManager\Controller\Adminhtml\Index\MassStatus implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Ui\Component\MassAction\Filter $filter, \Magento\Eav\Api\AttributeRepositoryInterface $attributeRepository, \Magento\Config\Model\ResourceModel\Config $config, \Swissup\FieldManager\Helper\BackendGrid $gridHelper, $collectionFactory = null)
    {
        $this->___init();
        parent::__construct($context, $filter, $attributeRepository, $config, $gridHelper, $collectionFactory);
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
