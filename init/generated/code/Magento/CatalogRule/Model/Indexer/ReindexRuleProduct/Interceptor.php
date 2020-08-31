<?php
namespace Magento\CatalogRule\Model\Indexer\ReindexRuleProduct;

/**
 * Interceptor class for @see \Magento\CatalogRule\Model\Indexer\ReindexRuleProduct
 */
class Interceptor extends \Magento\CatalogRule\Model\Indexer\ReindexRuleProduct implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\ResourceConnection $resource, \Magento\Catalog\Model\ResourceModel\Indexer\ActiveTableSwitcher $activeTableSwitcher, \Magento\CatalogRule\Model\Indexer\IndexerTableSwapperInterface $tableSwapper = null)
    {
        $this->___init();
        parent::__construct($resource, $activeTableSwitcher, $tableSwapper);
    }

    /**
     * {@inheritdoc}
     */
    public function execute(\Magento\CatalogRule\Model\Rule $rule, $batchCount, $useAdditionalTable = false)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'execute');
        if (!$pluginInfo) {
            return parent::execute($rule, $batchCount, $useAdditionalTable);
        } else {
            return $this->___callPlugins('execute', func_get_args(), $pluginInfo);
        }
    }
}
