<?php
namespace Magento\TargetRule\Model\Indexer\TargetRule\Rule\Product;

/**
 * Interceptor class for @see \Magento\TargetRule\Model\Indexer\TargetRule\Rule\Product
 */
class Interceptor extends \Magento\TargetRule\Model\Indexer\TargetRule\Rule\Product implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\TargetRule\Model\Indexer\TargetRule\Rule\Product\Action\Row $ruleProductIndexerRow, \Magento\TargetRule\Model\Indexer\TargetRule\Rule\Product\Action\Rows $ruleProductIndexerRows, \Magento\TargetRule\Model\Indexer\TargetRule\Action\Full $ruleProductIndexerFull, \Magento\TargetRule\Model\Indexer\TargetRule\Product\Rule\Processor $productRuleProcessor, \Magento\TargetRule\Model\Indexer\TargetRule\Rule\Product\Processor $ruleProductProcessor)
    {
        $this->___init();
        parent::__construct($ruleProductIndexerRow, $ruleProductIndexerRows, $ruleProductIndexerFull, $productRuleProcessor, $ruleProductProcessor);
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
