<?php
namespace Itatiaia\SitemapXml\Controller\Adminhtml\Sitemap;

class Create extends \Magento\Framework\App\Action\Action
{
	public function execute()
	{
        $this->_forward('edit');
	}
}
