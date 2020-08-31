<?php
namespace Potato\ImageOptimization\Controller\Adminhtml\Filter\Error;

/**
 * Interceptor class for @see \Potato\ImageOptimization\Controller\Adminhtml\Filter\Error
 */
class Interceptor extends \Potato\ImageOptimization\Controller\Adminhtml\Filter\Error implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Ui\Api\BookmarkManagementInterface $bookmarkManagement, \Magento\Ui\Api\BookmarkRepositoryInterface $bookmarkRepository, \Magento\Ui\Api\Data\BookmarkInterfaceFactory $bookmarkDataFactory, \Magento\Framework\Json\EncoderInterface $jsonEncode, \Magento\Authorization\Model\UserContextInterface $userContext, \Potato\ImageOptimization\Logger\Logger $logger)
    {
        $this->___init();
        parent::__construct($context, $bookmarkManagement, $bookmarkRepository, $bookmarkDataFactory, $jsonEncode, $userContext, $logger);
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
