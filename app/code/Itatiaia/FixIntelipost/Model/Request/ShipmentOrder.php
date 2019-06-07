<?php
namespace Itatiaia\FixIntelipost\Model\Request;

class ShipmentOrder extends \Intelipost\Push\Model\Request\ShipmentOrder
{
    public function shipmentOrder($collectionData)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $invoiceObj = $this->_shipment_order_volume_invoice_obj->create();
        $volObj     = $this->_shipment_order_volume_array_obj->create();
        $ecObj      = $this->_end_customer_obj->create();

        $this->order_number                    = $collectionData['order_number'];
        $this->sales_order_number              = $collectionData['increment_id'];
        $this->quote_id                        = $collectionData['quote_id'];
        $this->delivery_method_id              = $collectionData['delivery_method_id'];
        $this->estimated_delivery_date         = $objectManager->get('\Itatiaia\FixIntelipost\Helper\Estimate')->getBusinessDays($collectionData['order_number']);
//        $this->estimated_delivery_date         = str_replace(' ', 'T', $collectionData['delivery_estimate_date_exact_iso']);
        $this->customer_shipping_costs         = $collectionData['customer_shipping_costs'];
        $this->provider_shipping_costs         = $collectionData['provider_shipping_costs'];
        $this->sales_channel                   = $collectionData['sales_channel'];
        $this->scheduled                       = (int)$collectionData['scheduled'];
        $this->scheduling_window_start         = $collectionData['scheduling_window_start'];
        $this->scheduling_window_end           = $collectionData['scheduling_window_end'];
        $this->shipment_order_type             = $collectionData['shipment_order_type'];
        $this->shipment_order_sub_type         = $collectionData['shipment_order_sub_type'];
        $this->shipment_order_volume_invoice   = $invoiceObj->getInformation($collectionData['order_number']);
        $this->shipment_order_volume_array     = $volObj->getInformation($collectionData['volumes'], $this->shipment_order_volume_invoice);
        $this->end_customer                    = $ecObj->getInformation($collectionData['entity_id'], $collectionData['customer_taxvat']);
        if($this->_scopeConfig->getValue('intelipost_push/order_status/create_and_ship')){
            $this->shipped_date                = $this->getNowDateTime();
        }
        $this->created                         = $this->getNowDateTime();
        //$this->origin_warehouse_code         = null;

        $requestBody = $this->prepareShipmentRequestBody();
        $this->_helper->logIntelipost(json_encode($requestBody));

        $this->sendShipmentRequest(json_encode($requestBody), $collectionData);
    }

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
