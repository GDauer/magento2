<?php

namespace DigitalHub\ProductAlertReport\Controller\Adminhtml;

use Magento\Backend\App\Action;

abstract class Index extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Magento_Reports::report';

    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    protected $_fileFactory;

    public function __construct(
        Action\Context $context,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory
    )
    {
        $this->_fileFactory = $fileFactory;
        parent::__construct($context);
    }
}