<?php
namespace DigitalHub\MenuManager\Model;

class Menu
    extends \Magento\Framework\Model\AbstractModel
    implements \Magento\Framework\DataObject\IdentityInterface
{
	const CACHE_TAG = 'digitalhub_menumanager_menu';
	protected $_cacheTag = 'digitalhub_menumanager_menu';
	protected $_eventPrefix = 'digitalhub_menumanager_menu';

	protected function _construct()
	{
		$this->_init('DigitalHub\MenuManager\Model\ResourceModel\Menu');
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
