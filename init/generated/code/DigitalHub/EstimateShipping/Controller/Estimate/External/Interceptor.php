<?php
namespace DigitalHub\EstimateShipping\Controller\Estimate\External;

/**
 * Interceptor class for @see \DigitalHub\EstimateShipping\Controller\Estimate\External
 */
class Interceptor extends \DigitalHub\EstimateShipping\Controller\Estimate\External implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $pageFactory, \Magento\Framework\Registry $coreRegistry, \Magento\Framework\Controller\ResultFactory $result, \Magento\Catalog\Model\ProductFactory $productFactory)
    {
        $this->___init();
        parent::__construct($context, $pageFactory, $coreRegistry, $result, $productFactory);
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
