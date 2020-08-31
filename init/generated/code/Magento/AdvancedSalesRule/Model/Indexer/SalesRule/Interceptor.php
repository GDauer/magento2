<?php
namespace Magento\AdvancedSalesRule\Model\Indexer\SalesRule;

/**
 * Interceptor class for @see \Magento\AdvancedSalesRule\Model\Indexer\SalesRule
 */
class Interceptor extends \Magento\AdvancedSalesRule\Model\Indexer\SalesRule implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\AdvancedSalesRule\Model\Indexer\SalesRule\Action\FullFactory $fullActionFactory, \Magento\AdvancedSalesRule\Model\Indexer\SalesRule\Action\RowsFactory $rowsActionFactory)
    {
        $this->___init();
        parent::__construct($fullActionFactory, $rowsActionFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function executeFull()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'executeFull');
        if (!$pluginInfo) {
            return parent::executeFull();
        } else {
            return $this->___callPlugins('executeFull', func_get_args(), $pluginInfo);
        }
    }
}
