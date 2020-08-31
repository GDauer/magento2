<?php
namespace Magento\Framework\View\Element\UiComponent\DataProvider\Reporting;

/**
 * Interceptor class for @see \Magento\Framework\View\Element\UiComponent\DataProvider\Reporting
 */
class Interceptor extends \Magento\Framework\View\Element\UiComponent\DataProvider\Reporting implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\View\Element\UiComponent\DataProvider\CollectionFactory $collectionFactory, \Magento\Framework\View\Element\UiComponent\DataProvider\FilterPool $filterPool)
    {
        $this->___init();
        parent::__construct($collectionFactory, $filterPool);
    }

    /**
     * {@inheritdoc}
     */
    public function search(\Magento\Framework\Api\Search\SearchCriteriaInterface $searchCriteria)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'search');
        if (!$pluginInfo) {
            return parent::search($searchCriteria);
        } else {
            return $this->___callPlugins('search', func_get_args(), $pluginInfo);
        }
    }
}
