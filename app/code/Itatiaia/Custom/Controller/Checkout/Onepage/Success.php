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
        $logger = $objectManager->get('\Psr\Log\LoggerInterface');
        $checkoutSession = $objectManager->get('\Magento\Checkout\Model\Session');

        $quoteId = $checkoutSession->getLastSuccessQuoteId();
        $lastOrderId = $checkoutSession->getLastOrderId();
        $lastQuoteId = (string)$checkoutSession->getLastQuoteId();

        try {
            $order = $objectManager->get('Magento\Sales\Model\OrderFactory')->create()->load($lastOrderId);
            $logger->info('ORDER SUCCESS', ['increment_id' => $order->getIncrementId(), 'quoteId' => $quoteId, 'lastOrderId' => $lastOrderId, 'lastQuoteId' => $lastQuoteId]);
        } catch (\Exception $e){
            // apenas para não gerar execeção no fechamento do pedido
        }

        if((!$lastOrderId))
        {
            return false;
        }
        return true;
    }
}
