<?php
namespace Itatiaia\SitemapXml\Controller\Adminhtml\Sitemap;

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
        \Itatiaia\SitemapXml\Helper\Sitemap $sitemapHelper,
        \Itatiaia\SitemapXml\Model\SitemapFactory $sitemapFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $coreRegistry;
        $this->sitemapHelper = $sitemapHelper;
        $this->sitemapFactory = $sitemapFactory;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $sitemap = $this->sitemapFactory->create()->load($id);

        if(!$sitemap){
            $this->messageManager->addErrorMessage(__('Registro não encontrado'));
        } else {
            try {
                $this->sitemapHelper->generateSitemap($sitemap);
                $this->messageManager->addSuccessMessage('Arquivo gerado com sucesso!');
            } catch (Exception $e){
                $this->messageManager->addErrorMessage($e);
            }
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/');
    }
}
