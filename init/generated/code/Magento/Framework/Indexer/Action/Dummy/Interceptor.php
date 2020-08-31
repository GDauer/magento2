<?php
namespace Magento\Framework\Indexer\Action\Dummy;

/**
 * Interceptor class for @see \Magento\Framework\Indexer\Action\Dummy
 */
class Interceptor extends \Magento\Framework\Indexer\Action\Dummy implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct()
    {
        $this->___init();
    }

    /**
     * {@inheritdoc}
     */
    public function executeFull()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'executeFull');
        if (!$pluginInfo) {
            return parent::executeFull();
        } else {
            return $this->___callPlugins('executeFull', func_get_args(), $pluginInfo);
        }
    }
}
