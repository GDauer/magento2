<?php
namespace Itatiaia\SitemapXml\Controller\Adminhtml\Sitemap;

class Save extends \Magento\Backend\App\Action
{
    protected $resultPageFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\ResultFactory $resultFactory,
        \Itatiaia\SitemapXml\Model\SitemapFactory $sitemapFactory
    ) {
        parent::__construct($context);
        $this->resultFactory = $resultFactory;
        $this->sitemapFactory = $sitemapFactory;
    }

    public function execute()
    {
        $sitemapModel = $this->sitemapFactory->create();
        $post = $this->getRequest()->getPost('sitemap');
        $conditionsPost = $this->getRequest()->getPost('conditions');

        if(isset($post['id'])){ // Editing
            $sitemap = $sitemapModel->load($post['id']);
        } else {
            $sitemap = $sitemapModel;
            $sitemap->setCreatedAt(date('Y-m-d H:i:s'));
        }

        try {
            $conditions = [
                'category' => $conditionsPost['category']
            ];

    		$sitemap->setName($post['name']);
    		$sitemap->setFilename($post['filename']);
    		$sitemap->setType($post['type']);
            $sitemap->setActive($post['active']);
    		$sitemap->setStoreId($post['store_id']);
    		$sitemap->setConditions(json_encode($conditions));
    		$sitemap->setUpdatedAt(date('Y-m-d H:i:s'));
            $sitemap->save();

            $this->messageManager->addSuccess('O sitemap foi salvo com sucesso');
        } catch (\Exception $e){
            $this->messageManager->addError($e->getMessage());
        }

		$result = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
		$result->setPath('*/*/index');

		return $result;
    }
}
