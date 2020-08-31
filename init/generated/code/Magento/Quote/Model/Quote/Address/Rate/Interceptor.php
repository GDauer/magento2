<?php
namespace Magento\Quote\Model\Quote\Address\Rate;

/**
 * Interceptor class for @see \Magento\Quote\Model\Quote\Address\Rate
 */
class Interceptor extends \Magento\Quote\Model\Quote\Address\Rate implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null, \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function importShippingRate(\Magento\Quote\Model\Quote\Address\RateResult\AbstractResult $rate)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'importShippingRate');
        if (!$pluginInfo) {
            return parent::importShippingRate($rate);
        } else {
            return $this->___callPlugins('importShippingRate', func_get_args(), $pluginInfo);
        }
    }
}
