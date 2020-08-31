<?php
namespace Potato\ImageOptimization\Controller\Adminhtml\Image\MassRestore;

/**
 * Interceptor class for @see \Potato\ImageOptimization\Controller\Adminhtml\Image\MassRestore
 */
class Interceptor extends \Potato\ImageOptimization\Controller\Adminhtml\Image\MassRestore implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Potato\ImageOptimization\Model\ResourceModel\ImageRepository $imageRepository, \Potato\ImageOptimization\Manager\Restore $restoreManager, \Magento\Ui\Component\MassAction\Filter $filter, \Potato\ImageOptimization\Model\ResourceModel\Image\CollectionFactory $collection)
    {
        $this->___init();
        parent::__construct($context, $imageRepository, $restoreManager, $filter, $collection);
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
