<?php
namespace Magento\AdvancedSalesRule\Model\Rule\Condition\Product\Subselect;

/**
 * Interceptor class for @see \Magento\AdvancedSalesRule\Model\Rule\Condition\Product\Subselect
 */
class Interceptor extends \Magento\AdvancedSalesRule\Model\Rule\Condition\Product\Subselect implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Rule\Model\Condition\Context $context, \Magento\SalesRule\Model\Rule\Condition\Product $ruleConditionProduct, \Magento\AdvancedRule\Helper\CombineCondition $conditionHelper, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $ruleConditionProduct, $conditionHelper, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function loadArray($arr, $key = 'conditions')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'loadArray');
        if (!$pluginInfo) {
            return parent::loadArray($arr, $key);
        } else {
            return $this->___callPlugins('loadArray', func_get_args(), $pluginInfo);
        }
    }
}
