<?php

namespace DigitalHub\ProductAlertReport\Block\Adminhtml\Alert\Stock;

use DigitalHub\ProductAlertReport\Model\ResourceModel\Alert\Stock\CollectionFactory;
use Magento\Framework\App\ResourceConnection;
use Magento\Reports\Block\Adminhtml\Grid\AbstractGrid;

class Grid extends AbstractGrid
{
    /**
     * @var CollectionFactory
     */
    private $collectionStockFactory;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Reports\Model\ResourceModel\Report\Collection\Factory $resourceFactory,
        \Magento\Reports\Model\Grouped\CollectionFactory $collectionFactory,
        \Magento\Reports\Helper\Data $reportsData,
        CollectionFactory $collectionF,
        ResourceConnection $resourceConnection,
        \DigitalHub\ProductAlertReport\Model\ResourceModel\Alert\Stock\CollectionFactory $collectionStockFactory,
        array $data = []
    )
    {
        parent::__construct($context, $backendHelper, $resourceFactory, $collectionFactory, $reportsData, $data);
        $this->resource = $resourceConnection;
        $this->_collectionFactory = $collectionF;
        $this->collectionStockFactory = $collectionStockFactory;
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setCountTotals(true);
        $this->setCountSubTotals(true);
        $this->setPagerVisibility(true);
        $this->setFilterVisibility(true);
    }

    public function getResourceCollectionName()
    {
        return \DigitalHub\ProductAlertReport\Model\ResourceModel\Alert\Stock\Collection::class;
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'email',
            [
                'header' => __('E-mail'),
                'index' => 'email',
                'sortable' => true,
                'header_css_class' => 'col-email',
                'column_css_class' => 'col-email'
            ]
        );

        $this->addColumn(
            'sku',
            [
                'header' => __('SKU'),
                'index' => 'sku',
                'sortable' => true,
                'header_css_class' => 'col-sku',
                'column_css_class' => 'col-sku'
            ]
        );

        $this->addColumn(
            'status',
            [
                'header' => __('Status'),
                'index' => 'status',
                'sortable' => true,
                'header_css_class' => 'status',
                'column_css_class' => 'status',
                'filter_condition_callback' => array($this, '_filterStatus'),
                'renderer' => 'DigitalHub\ProductAlertReport\Block\Widget\Grid\Column\Renderer\Status'
            ]
        );

        $this->addColumn(
            'add_date',
            [
                'header' => __('Criado em'),
                'index' => 'add_date',
                'sortable' => true,
                'type' => 'datetime',
                'header_css_class' => 'col-add-date',
                'column_css_class' => 'col-add-date'
            ]
        );

        $this->addColumn(
            'send_date',
            [
                'header' => __('Enviado em'),
                'index' => 'send_date',
                'sortable' => true,
                'type' => 'datetime',
                'header_css_class' => 'col-send-date',
                'column_css_class' => 'col-send-date'
            ]
        );

        $this->addColumn(
            'send_count',
            [
                'header' => __('Qtd Enviados'),
                'index' => 'send_count',
                'sortable' => false,
                'header_css_class' => 'col-send-count',
                'column_css_class' => 'col-send-count'
            ]
        );

        $this->addExportType('*/*/exportStockCsv', __('CSV'));
        $this->addExportType('*/*/exportStockExcel', __('Excel XML'));

        return parent::_prepareColumns();
    }

    /**
     * Get filter from request using the same method in grid widget
     *
     * @param string $param
     * @return mixed|null
     */
    private function getFilter(string $param)
    {
        $filter = $this->getParam($this->getVarNameFilter(), null);

        if ($filter === null) {
            $filter = $this->_defaultFilter;
        }

        $data = [];
        if (is_string($filter)) {
            $data = $this->_backendHelper->prepareFilterString($filter);
            $data = array_merge($data, (array)$this->getRequest()->getPost($this->getVarNameFilter()));
        } elseif ($filter && is_array($filter)) {
            $data = $filter;
        } elseif (0 !== sizeof($this->_defaultFilter)) {
            $data = $this->_defaultFilter;
        }

        if (array_key_exists($param, $data)) {
            return $data[$param];
        }
        return null;
    }

    /**
     * Custom filter for status column
     * @param $collection
     * @param $column
     * @return $this
     */
    protected function _filterStatus($collection, $column)
    {
        return $this;
    }

    /**
     * Add our custom filter based on renderer logic
     *
     * @param $collection
     */
    private function _addCustomFilterToCollection($collection, $attribute)
    {
        $ids = [];
        foreach ($collection->getItems() as $key => $row) {
            $renderedValue = '';
            switch ($attribute) {
                case 'status':
                    $status = $row->getData('status');
                    if ($status === '1') {
                        $renderedValue = 'Enviado';
                    } else {
                        $renderedValue = 'Não Enviado';
                    }
                    break;
            }

            $filterValue = $this->getFilter($attribute);

            if (strpos($renderedValue, $filterValue) === false) {
                $ids[] = $row->getData('alert_stock_id');
            }
        }

        if (!empty($ids)) {
            $this->getCollection()->getSelect()->where('product_alert_stock.alert_stock_id NOT IN (?)', $ids);
        }
    }

    /**
     * @return \Magento\Backend\Block\Widget\Grid|AbstractGrid
     */
    protected function _prepareCollection()
    {
        $collection = $this->collectionStockFactory->create();
        if ($this->getFilter('status') !== null) {
            $this->_addCustomFilterToCollection($collection, 'status');
        }
        return parent::_prepareCollection();
    }

}