<?php
namespace Magento\CatalogPermissions\Model\Indexer\Category;

/**
 * Interceptor class for @see \Magento\CatalogPermissions\Model\Indexer\Category
 */
class Interceptor extends \Magento\CatalogPermissions\Model\Indexer\Category implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\CatalogPermissions\Model\Indexer\Category\Action\FullFactory $fullActionFactory, \Magento\CatalogPermissions\Model\Indexer\Category\Action\RowsFactory $rowsActionFactory, \Magento\Framework\Indexer\IndexerRegistry $indexerRegistry)
    {
        $this->___init();
        parent::__construct($fullActionFactory, $rowsActionFactory, $indexerRegistry);
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
