<?php
namespace Smile\ElasticsuiteCore\Search\Request\Query\Builder;

/**
 * Interceptor class for @see \Smile\ElasticsuiteCore\Search\Request\Query\Builder
 */
class Interceptor extends \Smile\ElasticsuiteCore\Search\Request\Query\Builder implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Smile\ElasticsuiteCore\Search\Request\Query\QueryFactory $queryFactory, \Smile\ElasticsuiteCore\Search\Request\Query\Fulltext\QueryBuilder $fulltextQueryBuilder, \Smile\ElasticsuiteCore\Search\Request\Query\Filter\QueryBuilder $filterQuerybuilder, \Smile\ElasticsuiteCore\Api\Search\ContextInterface $searchContext)
    {
        $this->___init();
        parent::__construct($queryFactory, $fulltextQueryBuilder, $filterQuerybuilder, $searchContext);
    }

    /**
     * {@inheritdoc}
     */
    public function createQuery(\Smile\ElasticsuiteCore\Api\Search\Request\ContainerConfigurationInterface $containerConfiguration, $query, array $filters, $spellingType)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'createQuery');
        if (!$pluginInfo) {
            return parent::createQuery($containerConfiguration, $query, $filters, $spellingType);
        } else {
            return $this->___callPlugins('createQuery', func_get_args(), $pluginInfo);
        }
    }
}
