<?php
namespace DigitalHub\MenuManager\Controller\Adminhtml\Menu;

class Categories extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->categoryFactory = $categoryFactory;
    }

    public function execute()
    {
        $result = $this->resultJsonFactory->create();

        $categoryModel = $this->categoryFactory->create();
        $categoriesArray = $categoryModel
                ->getCollection()
                ->addAttributeToSelect(['name','level','path','is_active'])
                ->addAttributeToFilter('is_active', 1)
                ->setOrder('path');

        $items = [];
        foreach($categoriesArray as $category){
            $items[] = $category->getData();
        }

        $result->setData([
            'items' => $items
        ]);

		return $result;
    }
}
