<?php
namespace DEG\CustomReports\Block\Adminhtml\Report;

use Magento\Framework\Registry;


class Grid extends \Magento\Backend\Block\Widget\Grid
{

    /**
     * @var Registry
     */
    private $registry;

    public function __construct(\Magento\Backend\Block\Template\Context $context,
                                \Magento\Backend\Helper\Data $backendHelper,
                                Registry $registery,
                                array $data = [])
    {
        parent::__construct($context, $backendHelper, $data);
        $this->registry = $registery;
    }

    function _prepareLayout(){

        /** @var $customReport \DEG\CustomReports\Model\CustomReport */
        $customReport = $this->registry->registry('current_customreport');
        /** @var $genericCollection \DEG\CustomReports\Model\GenericReportCollection */
        $genericCollection = $customReport->getGenericReportCollection();
        $columnList = $this->getColumnListFromCollection($genericCollection);
        if (count($columnList)) {
            $this->addColumnSet($columnList);
            $this->addGridExportBlock();
            $this->setCollection($genericCollection);
        }
        parent::_prepareLayout();
    }

    public function getColumnListFromCollection($collection){
        $columnsCollection = clone $collection;
        $columnsCollection->getSelect()->limitPage(1,1);
        $item = $columnsCollection->getFirstItem();
        return $item;
    }

    /**
     * @param $dataItem
     */
    public function addColumnSet($dataItem){
        /** @var $columnSet \Magento\Backend\Block\Widget\Grid\ColumnSet **/
        $columnSet = $this->_layout->createBlock('Magento\Backend\Block\Widget\Grid\ColumnSet', 'deg_customreports_grid.grid.columnSet');
        foreach ($dataItem->getData() as $key => $val) {
            if ($this->_defaultSort === false){
                $this->_defaultSort = $key;
            }
            /** @var $column \Magento\Backend\Block\Widget\Grid\Column **/
            $header = $key;
            $type = 'text';
            if( $key == 'created_at'){
                $header = 'Data Pedido';
                $type = "datetime";
                $data = [
                    'data' => ['header' => $header,
                        'index' => $key,
                        'type' => $type]
                ];

            } else if($key == "price_item" || $key == "price_order" ){
                $key == "price_item" ? $header = 'Preco Produto' : $header = "Total Pedido";
                $type = "price";
                $data = [
                    'data' => ['header' => $header,
                        'index' => $key,
                        'currency_code' => "BRL",
                        'type' => $type]
                ];

            } else if($key == "frete"){
                $type = "price";
                $header = "Frete";
                $data = [
                    'data' => ['header' => $header,
                        'index' => $key,
                        'currency_code' => "BRL",
                        'type' => $type]
                ];
            }else if($key == "statusorder"){
                $data = [
                    'data' => ['header' => $header,
                        'index' => $key,
                        'renderer' => "DEG\CustomReports\Ui\Component\Listing\Column\Renderer\Statusorder",
                        'type' => $type]
                ];

            } else {
                $data = [
                    'data' => ['header' => $header,
                        'index' => $key,
                        'type' => $type]
                ];
            }

            $column = $this->_layout->createBlock('Magento\Backend\Block\Widget\Grid\Column', 'deg_customreports_grid.grid.column.'.$key, $data);
            $columnSet->setChild($key, $column);
        }
        $this->setChild('grid.columnSet', $columnSet);
    }

    /**
     * Add the export block as a child block to the grid.
     *
     * @return $this
     */
    public function addGridExportBlock(){
        $exportArguments = [
            'data' => [
                'exportTypes'=> [
                    'csv' => [
                        'urlPath' => '*/*/exportCsv',
                        'label' => 'CSV'
                    ]
                ]
            ]
        ];

        $exportBlock = $this->_layout->createBlock('DEG\CustomReports\Block\Adminhtml\Report\Export', 'deg_customreports_grid.grid.export', $exportArguments);
        $this->setChild('grid.export', $exportBlock);
        $exportBlock->lazyPrepareLayout();
        return $this;
    }
}
