<?php
namespace Magento\Quote\Model\Quote\Address\CustomAttributeList;

/**
 * Interceptor class for @see \Magento\Quote\Model\Quote\Address\CustomAttributeList
 */
class Interceptor extends \Magento\Quote\Model\Quote\Address\CustomAttributeList implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct()
    {
        $this->___init();
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAttributes');
        if (!$pluginInfo) {
            return parent::getAttributes();
        } else {
            return $this->___callPlugins('getAttributes', func_get_args(), $pluginInfo);
        }
    }
}
