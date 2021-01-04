<?php
namespace DigitalHub\EstimateShipping\Controller\Estimate;

class External extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;

	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Framework\Registry $coreRegistry,
		\Magento\Framework\Controller\ResultFactory $result,
		\Magento\Catalog\Model\ProductFactory $productFactory
    )
	{
		$this->_pageFactory = $pageFactory;
		$this->_result = $result;
		$this->coreRegistry = $coreRegistry;
		$this->productFactory = $productFactory;
    	return parent::__construct($context);
	}

	public function execute()
	{
        $_product = $this->productFactory->create()->load((int)$this->getRequest()->getParam('id'));

        if(!$_product || $_product->getTypeId() != 'simple'){
            $resultRedirect = $this->_result->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setPath('/');
            return $resultRedirect;
        }

        $this->coreRegistry->register('current_product', $_product);
        return $this->_pageFactory->create();
	}
}
