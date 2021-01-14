<?php
namespace Itatiaia\SitemapXml\Controller\Adminhtml\Sitemap;

class Edit extends \Magento\Backend\App\Action
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
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $coreRegistry;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->_objectManager->get('Itatiaia\SitemapXml\Model\SitemapFactory')->create();

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('Registro não encontrado'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->_coreRegistry->register('itatiaia_sitemapxml_sitemap', $model);

        $resultPage = $this->resultPageFactory->create();

        return $resultPage;
    }
}
