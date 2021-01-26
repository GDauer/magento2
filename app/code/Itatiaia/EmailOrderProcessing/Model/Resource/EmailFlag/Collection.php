<?php
namespace Itatiaia\EmailOrderProcessing\Model\Resource\EmailFlag;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'id';
    protected $_eventPrefix = 'itatiaia_emailorderprocessing_email_flag_collection';
    protected $_eventObject = 'emailflag_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Itatiaia\EmailOrderProcessing\Model\EmailFlag', 'Itatiaia\EmailOrderProcessing\Model\Resource\EmailFlag');
    }
}
