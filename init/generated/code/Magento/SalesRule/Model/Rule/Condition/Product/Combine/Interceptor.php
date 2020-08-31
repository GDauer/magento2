<?php
namespace Magento\SalesRule\Model\Rule\Condition\Product\Combine;

/**
 * Interceptor class for @see \Magento\SalesRule\Model\Rule\Condition\Product\Combine
 */
class Interceptor extends \Magento\SalesRule\Model\Rule\Condition\Product\Combine implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Rule\Model\Condition\Context $context, \Magento\SalesRule\Model\Rule\Condition\Product $ruleConditionProduct, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $ruleConditionProduct, $data);
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
