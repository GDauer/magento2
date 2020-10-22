<?php
namespace Itatiaia\ProductTag\Model\Resource\Tag;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'id';
    protected $_eventPrefix = 'itatiaia_producttag_tag_collection';
    protected $_eventObject = 'tag_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Itatiaia\ProductTag\Model\Tag', 'Itatiaia\ProductTag\Model\Resource\Tag');
    }

}
