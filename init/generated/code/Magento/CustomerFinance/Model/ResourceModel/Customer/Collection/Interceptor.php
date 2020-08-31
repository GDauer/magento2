<?php
namespace Magento\CustomerFinance\Model\ResourceModel\Customer\Collection;

/**
 * Interceptor class for @see \Magento\CustomerFinance\Model\ResourceModel\Customer\Collection
 */
class Interceptor extends \Magento\CustomerFinance\Model\ResourceModel\Customer\Collection implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Data\Collection\EntityFactory $entityFactory, \Psr\Log\LoggerInterface $logger, \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy, \Magento\Framework\Event\ManagerInterface $eventManager, \Magento\Eav\Model\Config $eavConfig, \Magento\Framework\App\ResourceConnection $resource, \Magento\Eav\Model\EntityFactory $eavEntityFactory, \Magento\Eav\Model\ResourceModel\Helper $resourceHelper, \Magento\Framework\Validator\UniversalFactory $universalFactory, \Magento\Framework\Model\ResourceModel\Db\VersionControl\Snapshot $entitySnapshot, \Magento\Framework\DataObject\Copy\Config $fieldsetConfig, \Magento\Reward\Model\ResourceModel\Reward $resourceReward, \Magento\CustomerBalance\Model\ResourceModel\Balance $resourceBalance, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Framework\DB\Adapter\AdapterInterface $connection = null, $modelName = 'Magento\\Customer\\Model\\Customer')
    {
        $this->___init();
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $eavConfig, $resource, $eavEntityFactory, $resourceHelper, $universalFactory, $entitySnapshot, $fieldsetConfig, $resourceReward, $resourceBalance, $storeManager, $connection, $modelName);
    }

    /**
     * {@inheritdoc}
     */
    public function addAttributeToFilter($attribute, $condition = null, $joinType = 'inner')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addAttributeToFilter');
        if (!$pluginInfo) {
            return parent::addAttributeToFilter($attribute, $condition, $joinType);
        } else {
            return $this->___callPlugins('addAttributeToFilter', func_get_args(), $pluginInfo);
        }
    }
}
