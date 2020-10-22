<?php
namespace DigitalHub\FeedManager\Model;

class Feed
    extends \Magento\Framework\Model\AbstractModel
    implements \Magento\Framework\DataObject\IdentityInterface
{
	const CACHE_TAG = 'digitalhub_feedmanager_feed';
	protected $_cacheTag = 'digitalhub_feedmanager_feed';
	protected $_eventPrefix = 'digitalhub_feedmanager_feed';

	protected function _construct()
	{
		$this->_init('DigitalHub\FeedManager\Model\ResourceModel\Feed');
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
