<?php


namespace Itatiaia\GarantiaEstendida\Model\Config\Source;


use Magento\Catalog\Model\CategoryFactory;
use Magento\Framework\Option\ArrayInterface;

class Categories implements ArrayInterface
{
    /**
     * @var CategoryFactory
     */
    protected $categoryFactory;

    protected $_options;

    public function __construct
    (
        CategoryFactory $categoryFactory
    ) {
        $this->categoryFactory = $categoryFactory;
    }

    public function toOptionArray()
    {
        if(!$this->_options) {
            $collection = $this->categoryFactory->create()->getCollection()
                ->addFieldToSelect('*')
                ->addFieldToFilter('level', ['gt' => 1])
                ->addFieldToFilter('is_active', 1)
                ->setOrder('path');
        }

        $options = [];

        foreach ($collection as $category)
        {
            $prefix = "";
            $total = $category->getLevel()-2;

            for ($i=0; $i < $total; $i++) {
                $prefix .= "-- ";
            }

            $options[] = [
                'label' => $prefix . $category->getName(),
                'value' => $category->getId()
            ];
        }

        $this->_options = $options;

        return $this->_options;
    }
}