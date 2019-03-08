<?php
namespace Itatiaia\SitemapXml\Model\ResourceModel;

class Sitemap extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
	public function __construct(
		\Magento\Framework\Model\ResourceModel\Db\Context $context
	)
	{
		parent::__construct($context);
	}

	protected function _construct()
	{
		$this->_init('itatiaia_sitemapxml_sitemap', 'id');
	}
}
