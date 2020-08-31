<?php
namespace Magento\SalesRule\Model\Rule\Condition\Combine;

/**
 * Interceptor class for @see \Magento\SalesRule\Model\Rule\Condition\Combine
 */
class Interceptor extends \Magento\SalesRule\Model\Rule\Condition\Combine implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Rule\Model\Condition\Context $context, \Magento\Framework\Event\ManagerInterface $eventManager, \Magento\SalesRule\Model\Rule\Condition\Address $conditionAddress, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $eventManager, $conditionAddress, $data);
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
