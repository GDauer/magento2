<?php


namespace DigitalHub\RecentlyViewedWidget\Block\Product\Widget;

use Magento\CatalogWidget\Block\Product\ProductsList;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Reports\Block\Product\Viewed as ReportProductViewed;
//use Magento\Catalog\Block\Widget\RecentlyViewed as RecentlyViewed;

/**
 * Class ProductGrid
 * @package DigitalHub\RecentlyViewedWidget\Block\Product\Widget
 */
class ProductGrid extends ProductsList
{
    const DEFAULT_SORT_BY = 'name';
    const DEFAULT_SORT_ORDER = 'asc';
    const SORT_BY_PARAM = 'products_sort_by';
    const SORT_ORDER_PARAM = 'products_sort_order';

    /**
     * @var ReportProductViewed
     */
    protected $reportProductViewed;

    //protected $recentlyViewed;

    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Rule\Model\Condition\Sql\Builder $sqlBuilder,
        \Magento\CatalogWidget\Model\Rule $rule,
        \Magento\Widget\Helper\Conditions $conditionsHelper,
        ReportProductViewed $reportProductViewed,
        //RecentlyViewed $recentlyViewed,
        array $data = [],
        Json $json = null
    )
    {
        $this->reportProductViewed = $reportProductViewed;
        //$this->recentlyViewed = $recentlyViewed;
        parent::__construct($context, $productCollectionFactory, $catalogProductVisibility, $httpContext, $sqlBuilder, $rule, $conditionsHelper, $data, $json);
    }

    /**
     * Create Collection
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getProductCollection()
    {
        $collection = $this->reportProductViewed->getItemsCollection();
//        $conditions = $this->getConditions();
//        $conditions->collectValidatedAttributes($collection);
//        $this->sqlBuilder->attachConditionToCollection($collection, $conditions);
        return $collection;
    }

    /**
     * Create Collection
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function createCollection()
    {
        $collection = $this->getProductCollection();
        //$collection->addAttributeToSelect('name');
        $collection->setVisibility($this->catalogProductVisibility->getVisibleInCatalogIds());

        //$pageSize = $this->getPageSize();
        $pageSize = $this->getProductsCount();
        $currentPage = $this->getRequest()->getParam($this->getData('page_var_name'), 1);

        $collection = $this->_addProductAttributesAndPrices($collection)
            ->addStoreFilter()
            //->setPageSize($pageSize)
            ->setCurPage($currentPage)
            ->setOrder($this->getSortBy(), $this->getSortOrder());

        if ($this->getSortBy() !== self::DEFAULT_SORT_BY) {
            return $collection;
        }

        $collection->getSelect()->order($this->getSortBy() . ' ' . $this->getSortOrder());

//        try {
//            $order = $collection->getSelect()->getPart('order');
//            array_unshift($order, array_pop($order));
//            $collection->getSelect()->setPart('order', $order);
//        } catch (\Exception $e) {
//            $this->_logger->error(__("Error adding best seller sort order on product collection. Error: %1",
//                $e->getMessage()));
//        }

        return $collection;
    }

    /**
     * Return Sort By
     * @return array|mixed|null
     */
    public function getSortBy()
    {
        if (!$this->hasData(self::SORT_BY_PARAM)) {
            $this->setData(self::SORT_BY_PARAM, self::DEFAULT_SORT_BY);
        }
        return $this->getData(self::SORT_BY_PARAM);
    }

    /**
     * Return Sort Order type
     * @return array|mixed|null
     */
    public function getSortOrder()
    {
        if (!$this->hasData(self::SORT_ORDER_PARAM)) {
            $this->setData(self::SORT_ORDER_PARAM, self::DEFAULT_SORT_ORDER);
        }
        return $this->getData(self::SORT_ORDER_PARAM);
    }
}