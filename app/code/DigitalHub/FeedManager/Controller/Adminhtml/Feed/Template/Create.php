<?php
namespace DigitalHub\FeedManager\Controller\Adminhtml\Feed\Template;

class Create extends \Magento\Framework\App\Action\Action
{
	public function execute()
	{
        $this->_forward('edit');
	}
}
