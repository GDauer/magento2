<?php
namespace DigitalHub\MenuManager\Controller\Adminhtml\Menu;

class Create extends \Magento\Framework\App\Action\Action
{
	public function execute()
	{
        $this->_forward('edit');
	}
}
