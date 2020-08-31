<?php
namespace Magento\TargetRule\Model\Indexer\TargetRule\Product\Rule;

/**
 * Interceptor class for @see \Magento\TargetRule\Model\Indexer\TargetRule\Product\Rule
 */
class Interceptor extends \Magento\TargetRule\Model\Indexer\TargetRule\Product\Rule implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\TargetRule\Model\Indexer\TargetRule\Product\Rule\Action\Row $productRuleIndexerRow, \Magento\TargetRule\Model\Indexer\TargetRule\Product\Rule\Action\Rows $productRuleIndexerRows, \Magento\TargetRule\Model\Indexer\TargetRule\Action\Full $productRuleIndexerFull, \Magento\TargetRule\Model\Indexer\TargetRule\Rule\Product\Processor $ruleProductProcessor, \Magento\TargetRule\Model\Indexer\TargetRule\Product\Rule\Processor $productRuleProcessor, \Magento\TargetRule\Model\Indexer\TargetRule\Action\Clean $productRuleIndexerClean, \Magento\TargetRule\Model\Indexer\TargetRule\Product\Rule\Action\CleanDeleteProduct $productRuleIndexerCleanDeleteProduct)
    {
        $this->___init();
        parent::__construct($productRuleIndexerRow, $productRuleIndexerRows, $productRuleIndexerFull, $ruleProductProcessor, $productRuleProcessor, $productRuleIndexerClean, $productRuleIndexerCleanDeleteProduct);
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
