<?php
namespace Magento\Reminder\Model\Rule\Condition\Cart\Combine;

/**
 * Interceptor class for @see \Magento\Reminder\Model\Rule\Condition\Cart\Combine
 */
class Interceptor extends \Magento\Reminder\Model\Rule\Condition\Cart\Combine implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Rule\Model\Condition\Context $context, \Magento\Reminder\Model\ResourceModel\Rule $ruleResource, \Magento\Reminder\Model\Rule\Condition\Cart\CouponcodeFactory $couponFactory, \Magento\Reminder\Model\Rule\Condition\Cart\ItemsquantityFactory $itemsQtyFactory, \Magento\Reminder\Model\Rule\Condition\Cart\TotalquantityFactory $totalQtyFactory, \Magento\Reminder\Model\Rule\Condition\Cart\VirtualFactory $virtualFactory, \Magento\Reminder\Model\Rule\Condition\Cart\AmountFactory $amountFactory, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $ruleResource, $couponFactory, $itemsQtyFactory, $totalQtyFactory, $virtualFactory, $amountFactory, $data);
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
