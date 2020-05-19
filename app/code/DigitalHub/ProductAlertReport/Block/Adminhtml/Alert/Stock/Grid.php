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
                'type' => 'options',
                'options' => ['' => 'Todos', 0 => 'Não Enviado', 1 => 'Enviado'],
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

}