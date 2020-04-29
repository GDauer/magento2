<?php
namespace DigitalHub\FeedManager\Model\ResourceModel\Feed;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	protected $_idFieldName = 'id';
	protected $_eventPrefix = 'digitalhub_feedmanager_feed_collection';
	protected $_eventObject = 'feed_collection';

	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('DigitalHub\FeedManager\Model\Feed', 'DigitalHub\FeedManager\Model\ResourceModel\Feed');
	}
}
