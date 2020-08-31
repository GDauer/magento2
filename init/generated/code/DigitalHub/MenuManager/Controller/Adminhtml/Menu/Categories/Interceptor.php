<?php
namespace DigitalHub\MenuManager\Controller\Adminhtml\Menu\Categories;

/**
 * Interceptor class for @see \DigitalHub\MenuManager\Controller\Adminhtml\Menu\Categories
 */
class Interceptor extends \DigitalHub\MenuManager\Controller\Adminhtml\Menu\Categories implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \Magento\Catalog\Model\CategoryFactory $categoryFactory)
    {
        $this->___init();
        parent::__construct($context, $resultJsonFactory, $categoryFactory);
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
