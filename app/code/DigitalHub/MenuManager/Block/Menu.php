<?php
namespace DigitalHub\MenuManager\Block;

class Menu extends \Magento\Framework\View\Element\Template
{
    private $_menu;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \DigitalHub\MenuManager\Model\MenuFactory $menuFactory,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Cms\Model\PageFactory $cmsPageFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->menuFactory = $menuFactory;
        $this->categoryFactory = $categoryFactory;
        $this->cmsPageFactory = $cmsPageFactory;
        $this->storeManager = $storeManager;
    }

    public function getJsonData()
    {
        return json_decode($this->getMenu()->getJsonData());
    }

    public function parseItemData($item)
    {
        $itemDataOptions = new \Magento\Framework\DataObject((array)$item->data);
        $parsedItem = new \Magento\Framework\DataObject((array)$item);
        $defaultCategoryId = $this->storeManager->getStore()->getRootCategoryId();

        if($itemDataOptions->getType() == 'category'){
            $category = $this->categoryFactory->create()->load($itemDataOptions->getCategoryId());
            if($category->getId() != $defaultCategoryId){
                $itemDataOptions->setUrl($category->getUrl());
            }

            $parsedItem->setCategory($category);
            $parsedItem->setHasChildren(true);
        }

        if($itemDataOptions->getType() == 'cms'){
            $page = $this->cmsPageFactory->create()->load($itemDataOptions->getCmsPageId());
            $itemDataOptions->setUrl($this->getUrl($page->getIdentifier()));
        }

        if(!$parsedItem->getHasChildren(true)){
            if(count($parsedItem->getChildren())){
                $parsedItem->setHasChildren(true);
            }
        }

        $parsedItem->setOptions($itemDataOptions); // item data => options

        return $parsedItem;
    }

    public function getMenu()
    {
        if(!$this->_menu){
            $identifier = $this->getIdentifier();
            $menuModel = $this->menuFactory->create();
            $menu = $menuModel->load($identifier, 'identifier');
            $this->_menu = $menu;
        }

        return $this->_menu;
    }
}
