<?php
namespace DigitalHub\FeedManager\Controller\Adminhtml\Feed\Template;

class Save extends \Magento\Backend\App\Action
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
        $post = $this->getRequest()->getPost('feed_template');

        if(isset($post['id'])){ // Editing
            $feedTemplate = $feedTemplateModel->load($post['id']);
        } else {
            $feedTemplate = $feedTemplateModel;
            $feedTemplate->setCreatedAt(date('Y-m-d H:i:s'));
        }

        // echo "<pre>";
        // print_r($this->getRequest()->getPost());
        // exit;

        try {
    		$feedTemplate->setName($post['name']);
            $feedTemplate->setCode($post['code']);
    		$feedTemplate->setUpdatedAt(date('Y-m-d H:i:s'));
            $feedTemplate->save();

            $this->messageManager->addSuccess('O template foi salvo com sucesso');
        } catch (\Exception $e){
            $this->messageManager->addError($e->getMessage());
        }

		$result = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
		$result->setPath('*/*/index');

		return $result;
    }
}
