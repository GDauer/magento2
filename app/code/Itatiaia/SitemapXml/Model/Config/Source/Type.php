<?php
namespace Itatiaia\SitemapXml\Model\Config\Source;

class Type implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['label' => 'Produtos', 'value' => 'products'],
            ['label' => 'Categorias de produtos', 'value' => 'categories'],
            ['label' => 'Imagens de produtos', 'value' => 'product_images'],
            ['label' => 'Páginas CMS', 'value' => 'cms'],
            ['label' => 'Arquivo Index', 'value' => 'index'],
        ];
    }
}
