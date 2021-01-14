<?php


namespace Itatiaia\ProductTag\Model;


class Tag extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{

    const CACHE_TAG = 'itatiaia_producttag_tag';
    protected $_cacheTag = 'itatiaia_producttag_tag';
    protected $_eventPrefix = 'itatiaia_producttag_tag';

    protected function _construct()
    {
        $this->_init('Itatiaia\ProductTag\Model\Resource\Tag');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

}
