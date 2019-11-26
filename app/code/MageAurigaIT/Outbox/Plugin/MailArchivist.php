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

namespace MageAurigaIT\Outbox\Plugin;
        
class MailArchivist
{
    const EMAIL_SENT_SUCCESS = 1;
    const EMAIL_SENT_FAIL = 2;
    const EMAIL_SENT_UNDETERMINED = 3;
    
    /** @var \Magento\Framework\Mail\MessageInterface */
    private $message;
    
    public function __construct(
        \MageAurigaIT\Outbox\Model\SentEmailFactory $sentEmailFactory,
        \Magento\Framework\Mail\MessageInterface $message
    ) {
        $this->sentEmailFactory = $sentEmailFactory;
        $this->message = $message;
    }
    
    public function beforeSendMessage(\Magento\Framework\Mail\TransportInterface $subject)
    {
        if (class_exists("\MageAurigaIT\SmtpMailer\Helper\Config")) {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $smtpMailerConfig = $objectManager->create('\MageAurigaIT\SmtpMailer\Helper\Config');
            
            if ($smtpMailerConfig->isSmtpEnabled()) {
                return null;
            }
        }
        
        $this->archiveMessage($subject->getMessage());
        
        return null;
    }
    
    public function archiveMessage($msg, $status = self::EMAIL_SENT_UNDETERMINED)
    {
        $hdr = $msg->getHeaders();
        $from = isset($hdr['From'][0]) ? $hdr['From'][0] : null;
        $to = isset($hdr['To'][0]) ? $hdr['To'][0] : null;
        
        $sub = $msg->getSubject();
        
        $body = $msg->getBody()->getRawContent();
        
        $sentEmail = $this->sentEmailFactory->create();
        
        $sentEmail->setEmailFrom(
            $from
        )->setEmailTo(
            $to
        )->setEmailSub(
            $sub
        )->setEmailBody(
            $body
        )->setEmailStatus(
            $status
        )->setEmailRaw(
            serialize($msg)
        )->save();
        
        return $sentEmail->getId();
    }
    
    public function updateMessageStatus($message_id, $status)
    {
        $sentEmail = $this->sentEmailFactory->create();
        $sentEmail->load($message_id);
        $sentEmail->setEmailStatus($status)->save();
    }
}
