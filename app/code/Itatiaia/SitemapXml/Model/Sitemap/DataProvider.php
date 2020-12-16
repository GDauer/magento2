<?php
namespace Itatiaia\SitemapXml\Model\Sitemap;

use Itatiaia\SitemapXml\Model\ResourceModel\Sitemap\CollectionFactory;
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
            $this->loadedData[$model->getId()] = ['sitemap' => $modelData, 'conditions' => $conditions];
        }
        $data = $this->dataPersistor->get('itatiaia_sitemapxml_sitemap');

        if (!empty($data)) {
            $model = $this->collection->getNewEmptyItem();
            $modelData['conditions'] = json_decode([]);
            $model->setData($data);
            $this->loadedData[$model->getId()] = ['sitemap' => $model->getData(), 'conditions' => []];
            $this->dataPersistor->clear('itatiaia_sitemapxml_sitemap');
        }

        return $this->loadedData;
    }
}
