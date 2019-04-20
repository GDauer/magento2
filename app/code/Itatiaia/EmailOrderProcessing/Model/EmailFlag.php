<?php
namespace Itatiaia\EmailOrderProcessing\Model;

class EmailFlag
    extends \Magento\Framework\Model\AbstractModel
    implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'itatiaia_emailorderprocessing_email_flag';
	protected $_cacheTag = 'itatiaia_emailorderprocessing_email_flag';
	protected $_eventPrefix = 'itatiaia_emailorderprocessing_email_flag'; //

	protected function _construct()
	{
		$this->_init('Itatiaia\EmailOrderProcessing\Model\Resource\EmailFlag');
	}

    public function getIdentities()
	{
		return [self::CACHE_TAG . '_' . $this->getId()];
	}
}
