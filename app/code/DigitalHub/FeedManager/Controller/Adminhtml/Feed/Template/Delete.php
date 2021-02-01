<?php
namespace DigitalHub\FeedManager\Controller\Adminhtml\Feed\Template;

class Delete extends \Magento\Backend\App\Action
{
    protected $resultPageFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\ResultFactory $resultFactory,
        \DigitalHub\FeedManager\Model\Feed\TemplateFactory $feedTemplateFactory
    ) {
        parent::__construct($context);
        $this->resultFactory = $resultFactory;
        $this->feedTemplateFactory = $feedTemplateFactory;
    }

    public function execute()
    {
        $feedTemplateModel = $this->feedTemplateFactory->create();
        $id = $this->getRequest()->getParam('id');

        try {
            $feed = $feedTemplateModel->load($id);
            $feed->delete();

            $this->messageManager->addSuccess('O template foi removido com sucesso');
        } catch (\Exception $e){
            $this->messageManager->addError($e->getMessage());
        }

		$result = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
		$result->setPath('*/*/index');

		return $result;
    }
}
