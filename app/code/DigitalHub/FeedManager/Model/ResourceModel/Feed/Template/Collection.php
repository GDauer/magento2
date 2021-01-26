<?php
namespace DigitalHub\FeedManager\Model\ResourceModel\Feed\Template;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	protected $_idFieldName = 'id';
	protected $_eventPrefix = 'digitalhub_feedmanager_feed_template_collection';
	protected $_eventObject = 'feed_template_collection';

	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('DigitalHub\FeedManager\Model\Feed\Template', 'DigitalHub\FeedManager\Model\ResourceModel\Feed\Template');
	}
}
