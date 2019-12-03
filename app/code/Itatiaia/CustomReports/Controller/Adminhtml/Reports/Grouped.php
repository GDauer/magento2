<?php
namespace Itatiaia\CustomReports\Controller\Adminhtml\Reports;

class Grouped extends \Magento\Framework\App\Action\Action
{
	protected $resultPageFactory = false;

	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $resultPageFactory
	)
	{
		parent::__construct($context);
		$this->resultPageFactory = $resultPageFactory;
	}

	public function execute()
	{
		$resultPage = $this->resultPageFactory->create();
		$resultPage->getConfig()->getTitle()->prepend(__('Report de produtos agrupados vendidos'));

		return $resultPage;
	}
}
