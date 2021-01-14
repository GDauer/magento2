<?php
namespace Itatiaia\SitemapXml\Model\ResourceModel\Sitemap;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	protected $_idFieldName = 'id';
	protected $_eventPrefix = 'itatiaia_sitemapxml_sitemap_collection';
	protected $_eventObject = 'sitemap_collection';

	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('Itatiaia\SitemapXml\Model\Sitemap', 'Itatiaia\SitemapXml\Model\ResourceModel\Sitemap');
	}
}
