<?php

declare(strict_types=1);

namespace Treinamento\ModelsResourceModelsCollectionsExample\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class ResourceModel
 *
 * @package Treinamento\ModelsResourceModelsCollectionsExample\Model\ResourceModel
 */
class ResourceModel extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('pet_table', 'pet_id');
    }
}
