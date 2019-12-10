<?php
namespace DigitalHub\MenuManager\Model\ResourceModel\Menu;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	protected $_idFieldName = 'id';
	protected $_eventPrefix = 'digitalhub_menumanager_menu_collection';
	protected $_eventObject = 'menu_collection';

	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('DigitalHub\MenuManager\Model\Menu', 'DigitalHub\MenuManager\Model\ResourceModel\Menu');
	}
}
