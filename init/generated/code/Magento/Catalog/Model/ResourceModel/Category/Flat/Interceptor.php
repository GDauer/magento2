<?php
namespace Magento\Catalog\Model\ResourceModel\Category\Flat;

/**
 * Interceptor class for @see \Magento\Catalog\Model\ResourceModel\Category\Flat
 */
class Interceptor extends \Magento\Catalog\Model\ResourceModel\Category\Flat implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\ResourceModel\Db\Context $context, \Magento\Framework\Indexer\Table\StrategyInterface $tableStrategy, \Magento\Catalog\Model\CategoryFactory $categoryFactory, \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Catalog\Model\Config $catalogConfig, \Magento\Framework\Event\ManagerInterface $eventManager, $connectionName = null, \Magento\Catalog\Model\ResourceModel\Category\Flat\CollectionFactory $categoryFlatCollectionFactory = null)
    {
        $this->___init();
        parent::__construct($context, $tableStrategy, $categoryFactory, $categoryCollectionFactory, $storeManager, $catalogConfig, $eventManager, $connectionName, $categoryFlatCollectionFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function load(\Magento\Framework\Model\AbstractModel $object, $value, $field = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'load');
        if (!$pluginInfo) {
            return parent::load($object, $value, $field);
        } else {
            return $this->___callPlugins('load', func_get_args(), $pluginInfo);
        }
    }
}
