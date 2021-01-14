<?php
namespace DigitalHub\FeedManager\Model\ResourceModel;

class Feed extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
	public function __construct(
		\Magento\Framework\Model\ResourceModel\Db\Context $context
	)
	{
		parent::__construct($context);
	}

	protected function _construct()
	{
		$this->_init('digitalhub_feedmanager_feed', 'id');
	}
}
