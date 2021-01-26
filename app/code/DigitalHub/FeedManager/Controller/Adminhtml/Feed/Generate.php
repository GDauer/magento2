<?php
namespace DigitalHub\FeedManager\Controller\Adminhtml\Feed;

class Generate extends \Magento\Backend\App\Action
{
    protected $resultPageFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \DigitalHub\FeedManager\Helper\Feed $feedHelper,
        \DigitalHub\FeedManager\Model\FeedFactory $feedFactory,
        \DigitalHub\FeedManager\Model\Feed\TemplateFactory $feedTemplateFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $coreRegistry;
        $this->feedHelper = $feedHelper;
        $this->feedFactory = $feedFactory;
        $this->feedTemplateFactory = $feedTemplateFactory;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $feed = $this->feedFactory->create()->load($id);

        if(!$feed){
            $this->messageManager->addErrorMessage(__('Registro não encontrado'));
        } else {
            try {
                $template = $this->feedTemplateFactory->create()->load($feed->getTemplateId());
                $this->feedHelper->generateFeed($feed, $template);
                $this->messageManager->addSuccessMessage('Arquivo gerado com sucesso!');
            } catch (Exception $e){
                $this->messageManager->addErrorMessage($e);
            }
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/');
    }
}
