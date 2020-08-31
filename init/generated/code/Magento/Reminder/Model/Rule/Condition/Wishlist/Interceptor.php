<?php
namespace Magento\Reminder\Model\Rule\Condition\Wishlist;

/**
 * Interceptor class for @see \Magento\Reminder\Model\Rule\Condition\Wishlist
 */
class Interceptor extends \Magento\Reminder\Model\Rule\Condition\Wishlist implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Rule\Model\Condition\Context $context, \Magento\Reminder\Model\ResourceModel\Rule $ruleResource, \Magento\Framework\Stdlib\DateTime\DateTime $coreDate, \Magento\Framework\DB\Helper $resourceHelper, \Magento\Reminder\Model\Rule\Condition\Wishlist\CombineFactory $combineFactory, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $ruleResource, $coreDate, $resourceHelper, $combineFactory, $data);
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
