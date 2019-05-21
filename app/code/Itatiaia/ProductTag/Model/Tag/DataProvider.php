<?php

namespace Itatiaia\ProductTag\Model\Tag;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Itatiaia\ProductTag\Model\Resource\Tag\CollectionFactory $tagCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $tagCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();
        $this->loadedData = array();

        foreach ($items as $tag) {
            $this->loadedData[$tag->getId()]['tag'] = $tag->getData();
        }


        return $this->loadedData;

    }
}