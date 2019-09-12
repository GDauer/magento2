<?php

namespace Itatiaia\AdyenCustom\Plugin\Gateway\Http\Client;

use Magento\Checkout\Model\Session as CheckoutSession;
use Adyen\Payment\Gateway\Http\Client\TransactionPayment;
use Magento\Payment\Gateway\Http\TransferInterface;
use Magento\Payment\Gateway\Http\TransferBuilder;

class SetOrderItemsIntoRequestPlugin
{
    /** @var CheckoutSession */
    protected $checkoutSession;

    /** @var TransferBuilder */
    protected $transferBuilder;

    /**
     * @param CheckoutSession $checkoutSession
     * @param TransferBuilder $transferBuilder
     */
    public function __construct(
        CheckoutSession $checkoutSession,
        TransferBuilder $transferBuilder
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->transferBuilder = $transferBuilder;
    }

    public function beforePlaceRequest(
        TransactionPayment $subject,
        TransferInterface $transferObject
    ) {
        $request = $transferObject->getBody();
        $request['additionalData']['riskdata']['basket']['item'] = $this->mountQuoteItemsToRequest();
        $newTransferObject = $this->newTransferBuild($transferObject, $request);

        return ['transferObject' => $newTransferObject];
    }

    protected function mountQuoteItemsToRequest()
    {
        $result = [];
        $items = $this->checkoutSession->getQuote()->getItems();
        foreach ($items as $key => $item) {
            $result[$key] = [
                'itemID'        => (string)$item->getId(),
                'productTitle'  => (string)$item->getName(),
                'amountPerItem' => (string)$item->getPrice(),
                'sku'           => (string)$item->getSku(),
                'quantity'      => (string)$item->getQty()
            ];
        }
        return $result;
    }

    protected function newTransferBuild(TransferInterface $transferObject, array $request)
    {
        return $this->transferBuilder
            ->setMethod($transferObject->getMethod())
            ->setHeaders($transferObject->getHeaders())
            ->setBody($request)
            ->setAuthUsername($transferObject->getAuthUsername())
            ->setAuthPassword($transferObject->getAuthPassword())
            ->setUri($transferObject->getUri())
            ->build();
    }
}
