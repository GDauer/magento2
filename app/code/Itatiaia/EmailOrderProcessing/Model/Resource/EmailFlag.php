<?php
namespace Itatiaia\EmailOrderProcessing\Model\Resource;

class EmailFlag
    extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    )
    {
        parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_init('itatiaia_emailorderprocessing_email_flag', 'id');
    }
}
