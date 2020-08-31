<?php
namespace Magento\CatalogRule\Model\Indexer\Rule\RuleProductIndexer;

/**
 * Interceptor class for @see \Magento\CatalogRule\Model\Indexer\Rule\RuleProductIndexer
 */
class Interceptor extends \Magento\CatalogRule\Model\Indexer\Rule\RuleProductIndexer implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\CatalogRule\Model\Indexer\IndexBuilder $indexBuilder, \Magento\Framework\Event\ManagerInterface $eventManager)
    {
        $this->___init();
        parent::__construct($indexBuilder, $eventManager);
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
