<?php


namespace Itatiaia\Custom\Controller\Checkout\Onepage;


class Success extends \Magento\Checkout\Controller\Onepage
{
    public function execute()
    {
        $session = $this->getOnepage()->getCheckout();
        if (!$this->isValid()) {
            return $this->resultRedirectFactory->create()->setPath('checkout/cart');
        }
        $session->clearQuote();
        //@todo: Refactor it to match CQRS
        $resultPage = $this->resultPageFactory->create();
        $this->_eventManager->dispatch(
            'checkout_onepage_controller_success_action',
            ['order_ids' => [(string)$session->getLastOrderId()]]
        );
        return $resultPage;
    }

    public function isValid()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $checkoutSession = $objectManager->get('\Magento\Checkout\Model\Session');
        $quoteId = $checkoutSession->getLastSuccessQuoteId();
        $lastOrderId = $checkoutSession->getLastOrderId();
        $lastQuoteId = $checkoutSession->getLastQuoteId();

        if(!$quoteId)
        {
            return false;
        }
        if((!$lastOrderId || !$lastQuoteId))
        {
            return false;
        }
        return true;
    }
}
