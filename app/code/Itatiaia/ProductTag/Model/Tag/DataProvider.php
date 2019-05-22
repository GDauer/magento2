<?php

namespace Itatiaia\ProductTag\Model\Tag;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $storeManager;

    public function __construct(

        $name,
        $primaryFieldName,
        $requestFieldName,
        \Itatiaia\ProductTag\Model\Resource\Tag\CollectionFactory $tagCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $tagCollectionFactory->create();
        $this->storeManager = $storeManager;
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
            $this->loadedData[$tag->getId()]['tag']['image'] = [
                [
                    'name' => $tag->getImage(),
                    'url' => $this->getMediaUrl().$tag->getImage()
                ]
            ];
        }

//        foreach ($items as $tag) {
//            $this->loadedData[$tag->getId()]['tag'] = $tag->getData();
//            if ($tag->getImage()) {
//                $m[$tag->getId()]['tag']['name'] = $tag->getTagName();
//                $m[$tag->getId()]['tag']['url'] = $this->getMediaUrl().$tag->getImage();
//                $fullData = $this->loadedData;
//                $this->loadedData[$tag->getId()] = array_merge($fullData[$tag->getId()], $m);
//            }
//        }


        return $this->loadedData;

    }

    public function getMediaUrl()
    {
        $mediaUrl = $this->storeManager->getStore()
                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'tag/tmp/icon/';
        return $mediaUrl;
    }
}