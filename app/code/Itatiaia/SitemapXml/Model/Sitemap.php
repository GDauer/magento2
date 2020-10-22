<?php
namespace Itatiaia\SitemapXml\Model;

class Sitemap
    extends \Magento\Framework\Model\AbstractModel
    implements \Magento\Framework\DataObject\IdentityInterface
{
	const CACHE_TAG = 'itatiaia_sitemapxml_sitemap';
	protected $_cacheTag = 'itatiaia_sitemapxml_sitemap';
	protected $_eventPrefix = 'itatiaia_sitemapxml_sitemap';

	protected function _construct()
	{
		$this->_init('Itatiaia\SitemapXml\Model\ResourceModel\Sitemap');
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
