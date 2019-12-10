<?php
namespace DigitalHub\MenuManager\Controller\Adminhtml\Menu;

class Delete extends \Magento\Backend\App\Action
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
        $id = $this->getRequest()->getParam('id');

        try {
            $menu = $menuModel->load($id);
            $menu->delete();

            $this->messageManager->addSuccess('O menu foi removido com sucesso');
        } catch (\Exception $e){
            $this->messageManager->addError($e->getMessage());
        }

		$result = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
		$result->setPath('*/*/index');

		return $result;
    }
}
