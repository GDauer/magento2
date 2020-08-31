<?php
namespace GoCache\CDN\Controller\Adminhtml\Full\System\Config\TestToken;

/**
 * Interceptor class for @see \GoCache\CDN\Controller\Adminhtml\Full\System\Config\TestToken
 */
class Interceptor extends \GoCache\CDN\Controller\Adminhtml\Full\System\Config\TestToken implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \Magento\Framework\Filter\StripTags $tagFilter, \GoCache\CDN\Model\Api $api)
    {
        $this->___init();
        parent::__construct($context, $resultJsonFactory, $tagFilter, $api);
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
