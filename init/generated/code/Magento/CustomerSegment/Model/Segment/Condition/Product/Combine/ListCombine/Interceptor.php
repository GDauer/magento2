<?php
namespace Magento\CustomerSegment\Model\Segment\Condition\Product\Combine\ListCombine;

/**
 * Interceptor class for @see \Magento\CustomerSegment\Model\Segment\Condition\Product\Combine\ListCombine
 */
class Interceptor extends \Magento\CustomerSegment\Model\Segment\Condition\Product\Combine\ListCombine implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Rule\Model\Condition\Context $context, \Magento\CustomerSegment\Model\ConditionFactory $conditionFactory, \Magento\CustomerSegment\Model\ResourceModel\Segment $resourceSegment, \Magento\Quote\Model\ResourceModel\Quote $quoteResource, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $conditionFactory, $resourceSegment, $quoteResource, $data);
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
