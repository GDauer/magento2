<?php
namespace DigitalHub\FeedManager\Model\Feed;

class Template
    extends \Magento\Framework\Model\AbstractModel
    implements \Magento\Framework\DataObject\IdentityInterface
{
	const CACHE_TAG = 'digitalhub_feedmanager_feed_template';
	protected $_cacheTag = 'digitalhub_feedmanager_feed_template';
	protected $_eventPrefix = 'digitalhub_feedmanager_feed_template';

	protected function _construct()
	{
		$this->_init('DigitalHub\FeedManager\Model\ResourceModel\Feed\Template');
	}

	public function getIdentities()
	{
		return [self::CACHE_TAG . '_' . $this->getId()];
	}

	public function getDefaultValues()
	{
		$values = [];
		return $values;
	}
}
