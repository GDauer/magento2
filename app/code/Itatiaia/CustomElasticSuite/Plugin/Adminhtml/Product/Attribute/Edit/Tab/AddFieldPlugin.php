<?php


namespace Itatiaia\CustomElasticSuite\Plugin\Adminhtml\Product\Attribute\Edit\Tab;

use Smile\ElasticsuiteCatalog\Block\Plugin\Adminhtml\Product\Attribute\Edit\Tab\FrontPlugin;

/**
 * Class AddFieldPlugin
 * @package Itatiaia\CustomElasticSuite\Plugin\Adminhtml\Product\Attribute\Edit\Tab
 */
class AddFieldPlugin
{

    /**
     * Add the es_ignore_index_on_parent field in the elasticsuite_catalog_attribute_fieldset fieldset
     * @param FrontPlugin $subject
     * @param $result
     * @return mixed
     */
    public function afterAroundSetForm(FrontPlugin $subject, $result)
    {
        $fieldset = $result->getForm()->getElement('elasticsuite_catalog_attribute_fieldset');

        if (!$fieldset) {
            return $result;
        }

        $fieldset->addField(
            'es_ignore_index_on_parent',
            'select',
            [
                'name' => 'es_ignore_index_on_parent',
                'label' => __('Ignore Indexing on Parent Product'),
                'values' => [
                    ['value' => 0, 'label' => __('No')],
                    ['value' => 1, 'label' => __('Yes')]]
            ],
            'is_filterable_in_search'
        );
        return $result;
    }
}