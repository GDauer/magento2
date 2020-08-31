<?php
namespace Magento\Reminder\Model\Rule\Condition\Cart;

/**
 * Interceptor class for @see \Magento\Reminder\Model\Rule\Condition\Cart
 */
class Interceptor extends \Magento\Reminder\Model\Rule\Condition\Cart implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Rule\Model\Condition\Context $context, \Magento\Reminder\Model\ResourceModel\Rule $ruleResource, \Magento\Framework\Stdlib\DateTime\DateTime $dateModel, \Magento\Framework\DB\Helper $resourceHelper, \Magento\Reminder\Model\Rule\Condition\Cart\CombineFactory $combineFactory, \Magento\Quote\Model\ResourceModel\Quote $quoteResource, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $ruleResource, $dateModel, $resourceHelper, $combineFactory, $quoteResource, $data);
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
