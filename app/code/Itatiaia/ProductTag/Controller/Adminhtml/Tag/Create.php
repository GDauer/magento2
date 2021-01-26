<?php


namespace Itatiaia\ProductTag\Controller\Adminhtml\Tag;


class Create extends \Magento\Backend\App\Action
{
    private $resultPageFactory;

    public function __construct
    (
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();

        return $resultPage;
    }
}
