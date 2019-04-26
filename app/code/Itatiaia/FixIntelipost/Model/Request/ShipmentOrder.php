<?php
namespace Itatiaia\FixIntelipost\Model\Request;

class ShipmentOrder extends \Intelipost\Push\Model\Request\ShipmentOrder
{
    public function prepareShipmentRequestBody()
    {
        $requestBodyObj = parent::prepareShipmentRequestBody();
        $requestBodyObj->origin_zip_code = $this->_getOriginZipCode($this->order_number);
        return $requestBodyObj;
    }

    protected function _getOriginZipCode($order_increment_id)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $order = $objectManager->get('\Magento\Sales\Model\OrderFactory')->create();
        $order->loadByIncrementId($order_increment_id);
        $origin_zip_code = null;

        try {
            $intelipost_quote = json_decode($order->getIntelipostQuote());
            $origin_zip_code = $intelipost_quote->result->quotes[0]->origin_zip_code;
        } catch (\Exception $e){
            //
        }

        return $origin_zip_code;
    }
}
