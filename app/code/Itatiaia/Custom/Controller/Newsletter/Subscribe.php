<?php
namespace Itatiaia\Custom\Controller\Newsletter;

class Subscribe extends \Magento\Framework\App\Action\Action
{
    public function __construct
    (
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory,
        \Psr\Log\LoggerInterface $logger
    )
    {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->subscriberFactory = $subscriberFactory;
        $this->logger = $logger;
    }

    public function execute()
    {
        $result = $this->resultJsonFactory->create();

        $email = $this->getRequest()->getPostValue('email');

        $this->logger->info('EMAIL', [$email]);

        try {
            // subscribe news
            $collection = $this->subscriberFactory->create()->getCollection()->addFieldToFilter('subscriber_email', $email);
            if(!count($collection)) {
                $this->subscriberFactory->create()->subscribe($email);
                $result->setData([
                    'success' => true
                ]);
            } else {
                $result->setData([
                    'registered' => true
                ]);
            }

        } catch (\Exception $e){
            $result->setData([
                'error' => $e->getMessage()
            ]);
        }

        return $result;
    }
}
