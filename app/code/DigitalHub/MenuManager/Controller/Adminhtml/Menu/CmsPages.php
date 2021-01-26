<?php
namespace DigitalHub\MenuManager\Controller\Adminhtml\Menu;

class CmsPages extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Cms\Model\PageFactory $cmsPageFactory
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->cmsPageFactory = $cmsPageFactory;
    }

    public function execute()
    {
        $result = $this->resultJsonFactory->create();

        $pagesModel = $this->cmsPageFactory->create();
        $pagesArray = $pagesModel
                ->getCollection()
                ->addFieldToSelect(['title','identifier'])
                ->toArray();

        $result->setData([
            'items' => $pagesArray['items']
        ]);
		return $result;
    }
}
