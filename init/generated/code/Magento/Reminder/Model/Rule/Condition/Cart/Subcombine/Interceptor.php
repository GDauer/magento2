<?php
namespace Magento\Reminder\Model\Rule\Condition\Cart\Subcombine;

/**
 * Interceptor class for @see \Magento\Reminder\Model\Rule\Condition\Cart\Subcombine
 */
class Interceptor extends \Magento\Reminder\Model\Rule\Condition\Cart\Subcombine implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Rule\Model\Condition\Context $context, \Magento\Reminder\Model\ResourceModel\Rule $ruleResource, \Magento\Reminder\Model\Rule\Condition\Cart\StoreviewFactory $storeviewFactory, \Magento\Reminder\Model\Rule\Condition\Cart\SkuFactory $skuFactory, \Magento\Reminder\Model\Rule\Condition\Cart\AttributesFactory $attrFactory, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $ruleResource, $storeviewFactory, $skuFactory, $attrFactory, $data);
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
