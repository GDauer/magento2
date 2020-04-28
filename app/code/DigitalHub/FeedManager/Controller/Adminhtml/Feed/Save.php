<?php
namespace DigitalHub\FeedManager\Controller\Adminhtml\Feed;

class Save extends \Magento\Backend\App\Action
{
    protected $resultPageFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\ResultFactory $resultFactory,
        \DigitalHub\FeedManager\Model\FeedFactory $feedFactory
    ) {
        parent::__construct($context);
        $this->resultFactory = $resultFactory;
        $this->feedFactory = $feedFactory;
    }

    public function execute()
    {
        $feedModel = $this->feedFactory->create();
        $post = $this->getRequest()->getPost('feed');
        $conditionsPost = $this->getRequest()->getPost('conditions');

        if(isset($post['id'])){ // Editing
            $feed = $feedModel->load($post['id']);
        } else {
            $feed = $feedModel;
            $feed->setCreatedAt(date('Y-m-d H:i:s'));
        }

        try {
            $conditions = [
                'category' => $conditionsPost['category'],
                'only_in_stock' => $conditionsPost['only_in_stock']
            ];

    		$feed->setName($post['name']);
    		$feed->setFilename($post['filename']);
            $feed->setActive($post['active']);
    		$feed->setStoreId($post['store_id']);
    		$feed->setTemplateId($post['template_id']);
    		$feed->setConditions(json_encode($conditions));
    		$feed->setUpdatedAt(date('Y-m-d H:i:s'));
            $feed->save();

            $this->messageManager->addSuccess('O feed foi salvo com sucesso');
        } catch (\Exception $e){
            $this->messageManager->addError($e->getMessage());
        }

		$result = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
		$result->setPath('*/*/index');

		return $result;
    }
}
