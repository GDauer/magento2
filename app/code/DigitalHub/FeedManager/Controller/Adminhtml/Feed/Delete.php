<?php
namespace DigitalHub\FeedManager\Controller\Adminhtml\Feed;

class Delete extends \Magento\Backend\App\Action
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
        $id = $this->getRequest()->getParam('id');

        try {
            $feed = $feedModel->load($id);
            $feed->delete();

            $this->messageManager->addSuccess('O feed foi removido com sucesso');
        } catch (\Exception $e){
            $this->messageManager->addError($e->getMessage());
        }

		$result = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
		$result->setPath('*/*/index');

		return $result;
    }
}
