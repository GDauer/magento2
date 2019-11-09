<?php

declare(strict_types=1);

namespace Treinamento\ModelsResourceModelsCollectionsExample\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\Action;
use Treinamento\ModelsResourceModelsCollectionsExample\Model\ResourceModel\Collection\CollectionFactory as Collection;

/**
 * Class Index
 *
 * @package Treinamento\ModelsResourceModelsCollectionsExample\Controller\Index
 */
class Index extends Action implements HttpGetActionInterface
{
    /** @var Collection $collectionFactory */
    private $collectionFactory;

    /**
     * Index constructor.
     *
     * @param Context $context
     * @param Collection $myCollection
     */
    public function __construct(Context $context, Collection $myCollection)
    {
        $this->collectionFactory = $myCollection;
        parent::__construct($context);
    }

    /**
     * {@inheritDoc}
     */
    public function execute()
    {
        $resultPage   = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $myCollection = $this->collectionFactory->create();
        //$myData       = $myCollection->getData(); //return all data as array

        $myCollection->addFieldToSelect('pet_type');

        $myFilteredData = $myCollection->getData();
        $resultPage->setData($myFilteredData);

        return $resultPage;
    }
}
