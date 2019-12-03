<?php

namespace MageAurigaIT\Outbox\Model\ResourceModel\SentEmail;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'id';
    
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            'MageAurigaIT\Outbox\Model\SentEmail',
            'MageAurigaIT\Outbox\Model\ResourceModel\SentEmail'
        );
    }
}
