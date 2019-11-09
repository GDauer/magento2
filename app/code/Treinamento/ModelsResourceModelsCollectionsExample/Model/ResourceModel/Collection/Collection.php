<?php

declare(strict_types=1);

namespace Treinamento\ModelsResourceModelsCollectionsExample\Model\ResourceModel\Collection;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Treinamento\ModelsResourceModelsCollectionsExample\Model\Model;
use Treinamento\ModelsResourceModelsCollectionsExample\Model\ResourceModel\ResourceModel;

/**
 * Class Collection
 *
 * @package Treinamento\ModelsResourceModelsCollectionsExample\Model\ResourceModel\Collection
 */
class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
