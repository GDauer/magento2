<?php

/**
 * Admin Logger extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Auriga License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.aurigait.com/magento_extensions/license.txt
 *
 * @category       MageAurigaIT
 * @package        MageAurigaIT_AdminLogger
 * @copyright      Copyright (c) 2017
 * @license        http://www.aurigait.com/magento_extensions/license.txt Auriga License
 */

namespace MageAurigaIT\Outbox\Controller\Adminhtml\Outbox;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use MageAurigaIT\Outbox\Model\ResourceModel\SentEmail\CollectionFactory;
use Magento\Backend\App\Action;

class Clear extends Action
{

    /**
     * @var Filter
     */
    private $filter;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @param Context $context
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(Context $context, CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    /**
     * Clear action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $collection = $this->collectionFactory->create();
        try {
            $collection->walk('delete');
            $this->messageManager->addSuccess(__('Log cleared successfully.'));
        } catch (\Exception $e) {
            $this->messageManager->addError(__('Error occurred while deleting.<br/>Error message: %1', $e->getMessage()));
        }
        
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        
        return $resultRedirect->setPath('*/*/');
    }

    /**
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('MageAurigaIT_Outbox::outbox');
    }
}
