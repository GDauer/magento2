<?php
namespace Smile\ElasticsuiteCatalogRule\Model\Rule\Condition\Combine;

/**
 * Interceptor class for @see \Smile\ElasticsuiteCatalogRule\Model\Rule\Condition\Combine
 */
class Interceptor extends \Smile\ElasticsuiteCatalogRule\Model\Rule\Condition\Combine implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Rule\Model\Condition\Context $context, \Smile\ElasticsuiteCatalogRule\Model\Rule\Condition\ProductFactory $conditionFactory, \Smile\ElasticsuiteCore\Search\Request\Query\QueryFactory $queryFactory, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $conditionFactory, $queryFactory, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function loadArray($arr, $key = 'conditions')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'loadArray');
        if (!$pluginInfo) {
            return parent::loadArray($arr, $key);
        } else {
            return $this->___callPlugins('loadArray', func_get_args(), $pluginInfo);
        }
    }
}
