<?php
namespace Magento\TargetRule\Model\Actions\Condition\Combine;

/**
 * Interceptor class for @see \Magento\TargetRule\Model\Actions\Condition\Combine
 */
class Interceptor extends \Magento\TargetRule\Model\Actions\Condition\Combine implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Rule\Model\Condition\Context $context, \Magento\TargetRule\Model\Actions\Condition\Product\AttributesFactory $attributeFactory, \Magento\TargetRule\Model\Actions\Condition\Product\SpecialFactory $specialFactory, array $data = array(), \Magento\TargetRule\Model\Actions\Condition\Product\Attributes\SqlBuilder $conditionSqlBuilder = null)
    {
        $this->___init();
        parent::__construct($context, $attributeFactory, $specialFactory, $data, $conditionSqlBuilder);
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
