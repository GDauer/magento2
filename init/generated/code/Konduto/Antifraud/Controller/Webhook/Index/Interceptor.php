<?php
namespace Konduto\Antifraud\Controller\Webhook\Index;

/**
 * Interceptor class for @see \Konduto\Antifraud\Controller\Webhook\Index
 */
class Interceptor extends \Konduto\Antifraud\Controller\Webhook\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Konduto\Antifraud\Model\KondutoService $kondutoService, \Konduto\Antifraud\Helper\Data $helper, \Magento\Backend\App\Action\Context $context)
    {
        $this->___init();
        parent::__construct($kondutoService, $helper, $context);
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
