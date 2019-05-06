<?php
namespace Itatiaia\CustomReports\Model\Report;

use Itatiaia\CustomReports\Model\ResourceModel\Report\GroupedSalesCollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

class GroupedSalesDataProvider
    extends \Magento\Ui\DataProvider\AbstractDataProvider
    implements \Magento\Framework\View\Element\UiComponent\DataProvider\DataProviderInterface
{

    protected $collection;

    protected $dataPersistor;

    protected $loadedData;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param GroupedSalesCollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        GroupedSalesCollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $model) {
            $modelData = $model->getData();
            $this->loadedData[$model->getId()] = ['report_item' => $modelData];
        }
        $data = $this->dataPersistor->get('itatiaia_customreports_report_grouped_sales');

        if (!empty($data)) {
            $model = $this->collection->getNewEmptyItem();
            $model->setData($data);
            $this->loadedData[$model->getId()] = ['report_item' => $model->getData()];
            $this->dataPersistor->clear('itatiaia_customreports_report_grouped_sales');
        }

        return $this->loadedData;
    }
}
