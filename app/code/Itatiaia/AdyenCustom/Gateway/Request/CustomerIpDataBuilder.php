<?php
namespace Itatiaia\AdyenCustom\Gateway\Request;

use Magento\Payment\Gateway\Request\BuilderInterface;

/**
 * Class CustomerDataBuilder
 */
class CustomerIpDataBuilder extends \Adyen\Payment\Gateway\Request\CustomerIpDataBuilder
{
    /**
     * @param array $buildSubject
     * @return array
     */
    public function build(array $buildSubject)
    {
        /** @var \Magento\Payment\Gateway\Data\PaymentDataObject $paymentDataObject */
        $paymentDataObject = \Magento\Payment\Gateway\Helper\SubjectReader::readPayment($buildSubject);
        $payment = $paymentDataObject->getPayment();
        $fullOrder = $payment->getOrder();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $ip = $fullOrder->getXForwardedFor() ? $fullOrder->getXForwardedFor() : $fullOrder->getRemoteIp();

        if(count(explode(',', $ip)) > 1){
            $ip = trim(explode(',', $ip)[0]);
        }

        $objectManager->get('\Psr\Log\LoggerInterface')->info('NEW SHOPPER IP', [$ip]);

        return ['shopperIP' => $ip];
    }
}
