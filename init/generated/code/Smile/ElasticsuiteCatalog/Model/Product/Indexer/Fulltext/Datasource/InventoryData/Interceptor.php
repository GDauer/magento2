<?php
namespace Smile\ElasticsuiteCatalog\Model\Product\Indexer\Fulltext\Datasource\InventoryData;

/**
 * Interceptor class for @see \Smile\ElasticsuiteCatalog\Model\Product\Indexer\Fulltext\Datasource\InventoryData
 */
class Interceptor extends \Smile\ElasticsuiteCatalog\Model\Product\Indexer\Fulltext\Datasource\InventoryData implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Smile\ElasticsuiteCatalog\Model\ResourceModel\Product\Indexer\Fulltext\Datasource\InventoryData $resourceModel)
    {
        $this->___init();
        parent::__construct($resourceModel);
    }

    /**
     * {@inheritdoc}
     */
    public function addData($storeId, array $indexData)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addData');
        if (!$pluginInfo) {
            return parent::addData($storeId, $indexData);
        } else {
            return $this->___callPlugins('addData', func_get_args(), $pluginInfo);
        }
    }
}
