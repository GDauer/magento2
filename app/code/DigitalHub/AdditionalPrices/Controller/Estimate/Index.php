<?php
namespace DigitalHub\AdditionalPrices\Controller\Estimate;

class Index extends \Magento\Framework\App\Action\Action
{
    public function __construct
    (
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Psr\Log\LoggerInterface $logger
    )
    {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->logger = $logger;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultJson = $this->resultJsonFactory->create();
        $price = (float)$this->getRequest()->getParam('price');

        $html = $resultPage->getLayout()
                ->createBlock('\DigitalHub\AdditionalPrices\Block\Product\Additional')
                ->setTemplate('DigitalHub_AdditionalPrices::product/additional_prices.phtml')
                ->setProduct(false)
                ->setCustomPrice($price)
                ->toHtml();

        $resultJson->setData([
            'html' => $html
        ]);

        return $resultJson;
    }
}
