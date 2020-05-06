<?php


namespace DigitalHub\RecentlyViewedWidget\Block\Product\Widget;

use Magento\CatalogWidget\Block\Product\ProductsList;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Reports\Block\Product\Viewed as ReportProductViewed;

/**
 * Class ProductGrid
 * @package DigitalHub\RecentlyViewedWidget\Block\Product\Widget
 */
class ProductGrid extends ProductsList
{
    /**
     * @var ReportProductViewed
     */
    protected $reportProductViewed;

    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Rule\Model\Condition\Sql\Builder $sqlBuilder,
        \Magento\CatalogWidget\Model\Rule $rule,
        \Magento\Widget\Helper\Conditions $conditionsHelper,
        ReportProductViewed $reportProductViewed,
        array $data = [],
        Json $json = null
    )
    {
        $this->reportProductViewed = $reportProductViewed;
        parent::__construct($context, $productCollectionFactory, $catalogProductVisibility, $httpContext, $sqlBuilder, $rule, $conditionsHelper, $data, $json);
    }

    /**
     * Returns recently viewed collection.
     * @return \Magento\Reports\Model\ResourceModel\Product\Index\Collection\AbstractCollection
     */
    public function getProductCollection()
    {
        return $this->reportProductViewed->getItemsCollection();
    }

    /**
     * Override to solve core issue.
     * @return \Magento\Rule\Model\Condition\Combine
     */
    protected function getConditions()
    {
        $conditions = $this->getData('conditions_encoded')
            ? $this->getData('conditions_encoded')
            : $this->getData('conditions');
        if ($conditions) {
            $conditions = $this->conditionsHelper->decode($conditions);
        }
        if (!$conditions ) {
            $conditions = [];
        }
        foreach ($conditions as $key => $condition) {
            if (!empty($condition['attribute'])
                && in_array($condition['attribute'], ['special_from_date', 'special_to_date'])
            ) {
                $conditions[$key]['value'] = date('Y-m-d H:i:s', strtotime($condition['value']));
            }
        }
        $this->rule->loadPost(['conditions' => $conditions]);
        return $this->rule->getConditions();
    }
}