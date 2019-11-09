<?php

declare(strict_types=1);

namespace Treinamento\ModelsResourceModelsCollectionsExample\Model;

use Magento\Framework\Model\AbstractModel;
use Treinamento\ModelsResourceModelsCollectionsExample\Model\ResourceModel\ResourceModel;

/**
 * Class Model
 *
 * @package Treinamento\ModelsResourceModelsCollectionsExample\Model
 */
class Model extends AbstractModel
{
    protected $_eventPrefix = 'treinamento_model_sample_pet';

    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }
}
