<?php
namespace DigitalHub\FeedManager\Controller\Adminhtml\Feed;

class Create extends \Magento\Framework\App\Action\Action
{
	public function execute()
	{
        $this->_forward('edit');
	}
}
