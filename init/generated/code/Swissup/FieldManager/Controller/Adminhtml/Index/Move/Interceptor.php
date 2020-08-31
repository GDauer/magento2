<?php
namespace Swissup\FieldManager\Controller\Adminhtml\Index\Move;

/**
 * Interceptor class for @see \Swissup\FieldManager\Controller\Adminhtml\Index\Move
 */
class Interceptor extends \Swissup\FieldManager\Controller\Adminhtml\Index\Move implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Eav\Api\AttributeRepositoryInterface $attributeRepository, \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder, \Magento\Framework\Controller\Result\JsonFactory $jsonFactory)
    {
        $this->___init();
        parent::__construct($context, $attributeRepository, $searchCriteriaBuilder, $jsonFactory);
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
