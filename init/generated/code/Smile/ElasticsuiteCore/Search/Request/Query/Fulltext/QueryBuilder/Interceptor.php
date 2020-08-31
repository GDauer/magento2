<?php
namespace Smile\ElasticsuiteCore\Search\Request\Query\Fulltext\QueryBuilder;

/**
 * Interceptor class for @see \Smile\ElasticsuiteCore\Search\Request\Query\Fulltext\QueryBuilder
 */
class Interceptor extends \Smile\ElasticsuiteCore\Search\Request\Query\Fulltext\QueryBuilder implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Smile\ElasticsuiteCore\Search\Request\Query\QueryFactory $queryFactory, array $fieldFilters = array())
    {
        $this->___init();
        parent::__construct($queryFactory, $fieldFilters);
    }

    /**
     * {@inheritdoc}
     */
    public function create(\Smile\ElasticsuiteCore\Api\Search\Request\ContainerConfigurationInterface $containerConfig, $queryText, $spellingType, $boost = 1)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'create');
        if (!$pluginInfo) {
            return parent::create($containerConfig, $queryText, $spellingType, $boost);
        } else {
            return $this->___callPlugins('create', func_get_args(), $pluginInfo);
        }
    }
}
