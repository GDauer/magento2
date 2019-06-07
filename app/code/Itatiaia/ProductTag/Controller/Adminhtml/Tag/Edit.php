<?php
namespace Itatiaia\ProductTag\Controller\Adminhtml\Tag;

class Edit extends \Magento\Backend\App\Action
{
    protected $resultPageFactory;
    protected $tagFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Itatiaia\ProductTag\Model\TagFactory $tagFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $coreRegistry;
        $this->tagFactory = $tagFactory;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');

        if ($id) {
            $tag = $this->tagFactory->create()->load($id);
            if (!$tag->getId()) {
                $this->messageManager->addErrorMessage(__('Registro não encontrado'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->_coreRegistry->register('itatiaia_tag', $tag);

        $resultPage = $this->resultPageFactory->create();

        return $resultPage;
    }
}
