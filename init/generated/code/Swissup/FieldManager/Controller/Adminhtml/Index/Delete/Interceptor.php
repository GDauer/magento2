<?php
namespace Swissup\FieldManager\Controller\Adminhtml\Index\Delete;

/**
 * Interceptor class for @see \Swissup\FieldManager\Controller\Adminhtml\Index\Delete
 */
class Interceptor extends \Swissup\FieldManager\Controller\Adminhtml\Index\Delete implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Framework\Registry $registry, \Magento\Eav\Model\EntityFactory $eavEntityFactory, \Magento\Customer\Model\AttributeFactory $customerAttributeFactory, \Magento\Store\Model\WebsiteFactory $websiteFactory, \Magento\Catalog\Model\Product\UrlFactory $urlFactory, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \Magento\Eav\Model\Config $eavModelConfig, \Magento\Eav\Model\Entity\Attribute\SetFactory $eavAttributeSetFactory, \Swissup\FieldManager\Helper\Data $helper)
    {
        $this->___init();
        parent::__construct($context, $resultPageFactory, $registry, $eavEntityFactory, $customerAttributeFactory, $websiteFactory, $urlFactory, $resultJsonFactory, $eavModelConfig, $eavAttributeSetFactory, $helper);
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
