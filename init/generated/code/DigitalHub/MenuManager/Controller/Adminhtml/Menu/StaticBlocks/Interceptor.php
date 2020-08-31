<?php
namespace DigitalHub\MenuManager\Controller\Adminhtml\Menu\StaticBlocks;

/**
 * Interceptor class for @see \DigitalHub\MenuManager\Controller\Adminhtml\Menu\StaticBlocks
 */
class Interceptor extends \DigitalHub\MenuManager\Controller\Adminhtml\Menu\StaticBlocks implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \Magento\Cms\Model\BlockFactory $staticBlocksFactory)
    {
        $this->___init();
        parent::__construct($context, $resultJsonFactory, $staticBlocksFactory);
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
