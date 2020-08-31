<?php
namespace Smile\ElasticsuiteCore\Model\Search\RequestMapper;

/**
 * Interceptor class for @see \Smile\ElasticsuiteCore\Model\Search\RequestMapper
 */
class Interceptor extends \Smile\ElasticsuiteCore\Model\Search\RequestMapper implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct()
    {
        $this->___init();
    }

    /**
     * {@inheritdoc}
     */
    public function getSortOrders(\Smile\ElasticsuiteCore\Api\Search\Request\ContainerConfigurationInterface $containerConfiguration, \Magento\Framework\Api\Search\SearchCriteriaInterface $searchCriteria)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getSortOrders');
        if (!$pluginInfo) {
            return parent::getSortOrders($containerConfiguration, $searchCriteria);
        } else {
            return $this->___callPlugins('getSortOrders', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters(\Smile\ElasticsuiteCore\Api\Search\Request\ContainerConfigurationInterface $containerConfiguration, \Magento\Framework\Api\Search\SearchCriteriaInterface $searchCriteria)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getFilters');
        if (!$pluginInfo) {
            return parent::getFilters($containerConfiguration, $searchCriteria);
        } else {
            return $this->___callPlugins('getFilters', func_get_args(), $pluginInfo);
        }
    }
}
