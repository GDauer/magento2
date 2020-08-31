<?php
namespace Magento\CatalogWidget\Model\Rule\Condition\Combine;

/**
 * Interceptor class for @see \Magento\CatalogWidget\Model\Rule\Condition\Combine
 */
class Interceptor extends \Magento\CatalogWidget\Model\Rule\Condition\Combine implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Rule\Model\Condition\Context $context, \Magento\CatalogWidget\Model\Rule\Condition\ProductFactory $conditionFactory, array $data = array(), array $excludedAttributes = array())
    {
        $this->___init();
        parent::__construct($context, $conditionFactory, $data, $excludedAttributes);
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
