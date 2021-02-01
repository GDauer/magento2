<?php

namespace MageAurigaIT\Outbox\Model\ResourceModel;

class SentEmail extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('mageaurigait_outbox_sent_emails', 'id');
    }
}
