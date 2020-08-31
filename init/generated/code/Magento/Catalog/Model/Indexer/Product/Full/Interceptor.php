<?php
namespace Magento\Catalog\Model\Indexer\Product\Full;

/**
 * Interceptor class for @see \Magento\Catalog\Model\Indexer\Product\Full
 */
class Interceptor extends \Magento\Catalog\Model\Indexer\Product\Full implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Indexer\IndexerRegistry $indexerRegistry, array $indexerList)
    {
        $this->___init();
        parent::__construct($indexerRegistry, $indexerList);
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
