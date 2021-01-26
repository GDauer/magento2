<?php
namespace Itatiaia\ProductTag\Model\Resource;

class Tag extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    )
    {
        parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_init('itatiaia_product_tag', 'id');
    }
}
