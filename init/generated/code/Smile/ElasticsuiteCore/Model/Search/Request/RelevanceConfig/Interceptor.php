<?php
namespace Smile\ElasticsuiteCore\Model\Search\Request\RelevanceConfig;

/**
 * Interceptor class for @see \Smile\ElasticsuiteCore\Model\Search\Request\RelevanceConfig
 */
class Interceptor extends \Smile\ElasticsuiteCore\Model\Search\Request\RelevanceConfig implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Config\ReinitableConfigInterface $config, \Magento\Framework\Event\ManagerInterface $eventManager, \Magento\Config\Model\Config\Structure $configStructure, \Magento\Framework\DB\TransactionFactory $transactionFactory, \Magento\Config\Model\Config\Loader $configLoader, \Magento\Framework\App\Config\ValueFactory $configValueFactory, \Magento\Store\Model\StoreManagerInterface $storeManager, \Smile\ElasticsuiteCore\Model\Search\Request\Source\Containers $containersSource, \Smile\ElasticsuiteCore\Search\Request\RelevanceConfig\App\Config\ScopePool $scopePool, array $data = array())
    {
        $this->___init();
        parent::__construct($config, $eventManager, $configStructure, $transactionFactory, $configLoader, $configValueFactory, $storeManager, $containersSource, $scopePool, $data);
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
