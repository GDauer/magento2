<?php
namespace DigitalHub\MenuManager\Controller\Adminhtml\Menu;

class Save extends \Magento\Backend\App\Action
{
    protected $resultPageFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\ResultFactory $resultFactory,
        \DigitalHub\MenuManager\Model\MenuFactory $menuFactory
    ) {
        parent::__construct($context);
        $this->resultFactory = $resultFactory;
        $this->menuFactory = $menuFactory;
    }

    public function execute()
    {
        $menuModel = $this->menuFactory->create();
        $post = $this->getRequest()->getPost('menu');

        if(isset($post['id'])){ // Editing
            $menu = $menuModel->load($post['id']);
        } else {
            $menu = $menuModel;
            $menu->setCreatedAt(date('Y-m-d H:i:s'));
        }

        try {
    		$menu->setName($post['name']);
    		$menu->setIdentifier($post['identifier']);
    		// $menu->setType($post['type']);
    		$menu->setActive($post['active']);
    		$menu->setJsonData($post['json_data']);
    		$menu->setUpdatedAt(date('Y-m-d H:i:s'));
            $menu->save();

            $this->messageManager->addSuccess('O menu foi salvo com sucesso');
        } catch (\Exception $e){
            $this->messageManager->addError($e->getMessage());
        }

		$result = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
		$result->setPath('*/*/index');

		return $result;
    }
}
