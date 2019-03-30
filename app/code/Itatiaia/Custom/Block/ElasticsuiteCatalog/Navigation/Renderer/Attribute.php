<?php
namespace Itatiaia\Custom\Block\ElasticsuiteCatalog\Navigation\Renderer;

class Attribute extends \Smile\ElasticsuiteCatalog\Block\Navigation\Renderer\Attribute
{
    public function getJsLayout()
    {
        $filterItems    = $this->getFilter()->getItems();

        $jsLayoutConfig = [
            'component'           => self::JS_COMPONENT,
            'maxSize'             => (int) $this->getFilter()->getAttributeModel()->getFacetMaxSize(),
            'displayProductCount' => (bool) $this->displayProductCount(),
            'hasMoreItems'        => (bool) $this->getFilter()->hasMoreItems(),
            'ajaxLoadUrl'         => $this->getAjaxLoadUrl(),
        ];

        foreach ($filterItems as $item) {
            $itemArray = $item->toArray(['label', 'count', 'url', 'is_selected', 'value']);
            $itemArray['request_var'] = $this->getFilter()->getRequestVar();
            $jsLayoutConfig['items'][] = $itemArray;
        }

        return json_encode($jsLayoutConfig);
    }
}
