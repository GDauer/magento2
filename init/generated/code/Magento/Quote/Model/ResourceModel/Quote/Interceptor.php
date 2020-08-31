<?php
namespace Magento\Quote\Model\ResourceModel\Quote;

/**
 * Interceptor class for @see \Magento\Quote\Model\ResourceModel\Quote
 */
class Interceptor extends \Magento\Quote\Model\ResourceModel\Quote implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\ResourceModel\Db\Context $context, \Magento\Framework\Model\ResourceModel\Db\VersionControl\Snapshot $entitySnapshot, \Magento\Framework\Model\ResourceModel\Db\VersionControl\RelationComposite $entityRelationComposite, \Magento\SalesSequence\Model\Manager $sequenceManager, $connectionName = null)
    {
        $this->___init();
        parent::__construct($context, $entitySnapshot, $entityRelationComposite, $sequenceManager, $connectionName);
    }

    /**
     * {@inheritdoc}
     */
    public function subtractProductFromQuotes($product)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'subtractProductFromQuotes');
        if (!$pluginInfo) {
            return parent::subtractProductFromQuotes($product);
        } else {
            return $this->___callPlugins('subtractProductFromQuotes', func_get_args(), $pluginInfo);
        }
    }
}
