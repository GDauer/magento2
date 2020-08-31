<?php
namespace Smile\ElasticsuiteThesaurus\Model\Indexer\Thesaurus;

/**
 * Interceptor class for @see \Smile\ElasticsuiteThesaurus\Model\Indexer\Thesaurus
 */
class Interceptor extends \Smile\ElasticsuiteThesaurus\Model\Indexer\Thesaurus implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Smile\ElasticsuiteThesaurus\Model\ResourceModel\Indexer\Thesaurus $resourceModel, \Magento\Store\Model\StoreManagerInterface $storeManager, \Smile\ElasticsuiteThesaurus\Model\Indexer\IndexHandler $indexHandler)
    {
        $this->___init();
        parent::__construct($resourceModel, $storeManager, $indexHandler);
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
