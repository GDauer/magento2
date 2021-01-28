<?php
namespace DigitalHub\FeedManager\Model\Config\Source;

class Category implements \Magento\Framework\Option\ArrayInterface
{
    public function __construct(
        \Magento\Catalog\Model\CategoryFactory $categoryFactory
    )
    {
        $this->categoryFactory = $categoryFactory;
    }

    public function toOptionArray()
    {
        $categoryModel = $this->categoryFactory->create();
        $collection = $categoryModel
                ->getCollection()
                ->addAttributeToSelect(['name','level','path','is_active'])
                ->addAttributeToFilter('level', ['gteq' => 2])
                ->setOrder(['path','name']);

        $options = [['label' => ' -- Selecione -- ', 'value' => '']];
        foreach($collection as $category){
            $options[] = [
                'label' => $this->getLabelPrefix($category) . $category->getName(),
                'value' => $category->getId()
            ];
        }
        return $options;
    }

    private function getLabelPrefix($category)
    {
        $prefix = '';
        foreach(range(1,$category->getLevel() - 1) as $level){
            if($level > 1){
                $prefix.= '|--';
            }
        }
        return $prefix;
    }
}
