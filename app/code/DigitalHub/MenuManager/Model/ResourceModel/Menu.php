<?php
namespace DigitalHub\MenuManager\Model\ResourceModel;

class Menu extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
	public function __construct(
		\Magento\Framework\Model\ResourceModel\Db\Context $context
	)
	{
		parent::__construct($context);
	}

	protected function _construct()
	{
		$this->_init('digitalhub_menumanager_menu', 'id');
	}
}
