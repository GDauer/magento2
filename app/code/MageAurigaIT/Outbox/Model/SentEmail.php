<?php

namespace MageAurigaIT\Outbox\Model;

class SentEmail extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;
    
    /**
     * Sent Email cache tag
     */
    const CACHE_TAG = 'sent_email';
    
    public function __construct(
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->date = $date;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }
    
    /**
     * @return void
     */
    public function _construct()
    {
            $this->_init('MageAurigaIT\Outbox\Model\ResourceModel\SentEmail');
    }

    public function getIdentities()
    {
        $identities = [];
        $id = $this->getId();

        if ($id) {
            $identities = [self::CACHE_TAG . '_' . $id];
        }

        return $identities;
    }

    /**
     * Set date of last update for email record
     *
     * @return $this
     */
    public function beforeSave()
    {
        parent::beforeSave();
        $this->setCreatedAt($this->date->gmtDate());
        return $this;
    }
}
