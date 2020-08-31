<?php
namespace Magento\Reminder\Model\Rule\Condition\Combine\Root;

/**
 * Interceptor class for @see \Magento\Reminder\Model\Rule\Condition\Combine\Root
 */
class Interceptor extends \Magento\Reminder\Model\Rule\Condition\Combine\Root implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Rule\Model\Condition\Context $context, \Magento\Reminder\Model\ResourceModel\Rule $ruleResource, \Magento\Customer\Model\Config\Share $config, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $ruleResource, $config, $data);
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
