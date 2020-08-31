<?php
namespace Magento\Search\Model\Query;

/**
 * Interceptor class for @see \Magento\Search\Model\Query
 */
class Interceptor extends \Magento\Search\Model\Query implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\Context $context, \Magento\Framework\Registry $registry, \Magento\Search\Model\ResourceModel\Query\CollectionFactory $queryCollectionFactory, \Magento\Search\Model\SearchCollectionFactory $searchCollectionFactory, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null, \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $registry, $queryCollectionFactory, $searchCollectionFactory, $storeManager, $scopeConfig, $resource, $resourceCollection, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function loadByQuery($text)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'loadByQuery');
        if (!$pluginInfo) {
            return parent::loadByQuery($text);
        } else {
            return $this->___callPlugins('loadByQuery', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function save()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'save');
        if (!$pluginInfo) {
            return parent::save();
        } else {
            return $this->___callPlugins('save', func_get_args(), $pluginInfo);
        }
    }
}
