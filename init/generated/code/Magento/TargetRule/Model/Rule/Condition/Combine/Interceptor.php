<?php
namespace Magento\TargetRule\Model\Rule\Condition\Combine;

/**
 * Interceptor class for @see \Magento\TargetRule\Model\Rule\Condition\Combine
 */
class Interceptor extends \Magento\TargetRule\Model\Rule\Condition\Combine implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Rule\Model\Condition\Context $context, \Magento\TargetRule\Model\Rule\Condition\Product\AttributesFactory $attributesFactory, array $data = array(), \Magento\TargetRule\Model\Rule\Condition\Product\Attributes\SqlBuilder $conditionSqlBuilder = null)
    {
        $this->___init();
        parent::__construct($context, $attributesFactory, $data, $conditionSqlBuilder);
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
