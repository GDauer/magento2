<?php
namespace Swissup\CheckoutFields\Controller\Adminhtml\Field\Validate;

/**
 * Interceptor class for @see \Swissup\CheckoutFields\Controller\Adminhtml\Field\Validate
 */
class Interceptor extends \Swissup\CheckoutFields\Controller\Adminhtml\Field\Validate implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Cache\FrontendInterface $attributeLabelCache, \Magento\Framework\Registry $coreRegistry, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, array $multipleAttributeList = array())
    {
        $this->___init();
        parent::__construct($context, $attributeLabelCache, $coreRegistry, $resultPageFactory, $resultJsonFactory, $multipleAttributeList);
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
