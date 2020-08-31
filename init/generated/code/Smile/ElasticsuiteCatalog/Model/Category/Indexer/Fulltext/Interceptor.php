<?php
namespace Smile\ElasticsuiteCatalog\Model\Category\Indexer\Fulltext;

/**
 * Interceptor class for @see \Smile\ElasticsuiteCatalog\Model\Category\Indexer\Fulltext
 */
class Interceptor extends \Smile\ElasticsuiteCatalog\Model\Category\Indexer\Fulltext implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Smile\ElasticsuiteCatalog\Model\Category\Indexer\Fulltext\Action\Full $fullAction, \Magento\Framework\Indexer\SaveHandler\IndexerInterface $indexerHandler, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Framework\Search\Request\DimensionFactory $dimensionFactory)
    {
        $this->___init();
        parent::__construct($fullAction, $indexerHandler, $storeManager, $dimensionFactory);
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
