<?php
namespace DigitalHub\MenuManager\Controller\Adminhtml\Menu;

class StaticBlocks extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Cms\Model\BlockFactory $staticBlocksFactory
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->staticBlocksFactory = $staticBlocksFactory;
    }

    public function execute()
    {
        $result = $this->resultJsonFactory->create();
        $staticBlockModel = $this->staticBlocksFactory->create();
        $staticBlocksArray = $staticBlockModel
                ->getCollection()
                ->addFieldToSelect(['title','identifier'])
                ->toArray();

        $result->setData([
            'items' => $staticBlocksArray['items']
        ]);
		return $result;
    }
}
