<?php

/**
 * Outbox extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Auriga License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.aurigait.com/magento_extensions/license.txt
 *
 * @category      MageAurigaIT
 * @package       MageAurigaIT_Outbox
 * @copyright     Copyright (c) 2017
 * @license       http://www.aurigait.com/magento_extensions/license.txt Auriga License
 */

namespace MageAurigaIT\Outbox\Controller\Adminhtml\Outbox;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Action;
use Magento\Framework\Mail\TransportInterfaceFactory;
use Magento\Framework\App\Request\Http;
use MageAurigaIT\Outbox\Model\SentEmailFactory;

class Resend extends Action
{

    /**
     * @var PageFactory
     */
    private $resultPageFactory;
    
    /**
     * @var TransportInterfaceFactory
     */
    private $transportFactory;
    
    /**
     * @var Http
     */
    private $request;
    
    /**
     * @var SentEmailFactory
     */
    private $sentEmailFactory;
    
    /**
     * Index constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Http $request,
        TransportInterfaceFactory $transportFactory,
        SentEmailFactory $sentEmailFactory,
        Context $context,
        PageFactory $resultPageFactory
    ) {
        $this->request = $request;
        $this->transportFactory = $transportFactory;
        $this->sentEmailFactory = $sentEmailFactory;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $emailId = $this->request->getParam("id");
        $sentEmail = $this->sentEmailFactory->create();
        $sentEmail->load($emailId);
        
        $rawMessage = $sentEmail->getEmailRaw();
        if ($rawMessage) {
            $rawMessage = unserialize($sentEmail->getEmailRaw());
        }
        
        if ($rawMessage instanceof \Magento\Framework\Mail\MessageInterface) {
            $transport = $this->transportFactory->create(['message' => $rawMessage]);
            try {
                $transport->sendMessage();
                $this->messageManager->addSuccessMessage(
                    __("Email re-sent successfully.")
                );
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(
                    __("Error: %1", $e->getMessage())
                );
            }
        }
        
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
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
