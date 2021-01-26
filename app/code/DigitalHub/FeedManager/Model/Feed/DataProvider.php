<?php
namespace DigitalHub\FeedManager\Model\Feed;

use DigitalHub\FeedManager\Model\ResourceModel\Feed\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

class DataProvider
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
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
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
            $conditions = $modelData['conditions'] ? json_decode($modelData['conditions'], 1) : [];
            $this->loadedData[$model->getId()] = ['feed' => $modelData, 'conditions' => $conditions];
        }
        $data = $this->dataPersistor->get('digitalhub_feedmanager_feed');

        if (!empty($data)) {
            $model = $this->collection->getNewEmptyItem();
            $modelData['conditions'] = json_decode([]);
            $modelData['conditions_category'] = null;
            $model->setData($data);
            $this->loadedData[$model->getId()] = ['feed' => $model->getData(), 'conditions' => []];
            $this->dataPersistor->clear('digitalhub_feedmanager_feed');
        }

        return $this->loadedData;
    }
}
