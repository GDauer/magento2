<?php
namespace Itatiaia\FixIntelipost\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    public function getCustomOriginZipcodeByItems(Array $items, $destPostcode){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $config = $objectManager->get('\Magento\Framework\App\Config\ScopeConfigInterface');
        $logger = $objectManager->get('\Psr\Log\LoggerInterface');
        $defaultZipcode = $config->getValue('carriers/intelipost/source_zip');
        $zipcodeWarehouseAco = $config->getValue('carriers/itatiaia_fixintelipost/zipcode_warehouse_aco');
        $zipcodeRangeEletro = $config->getValue('carriers/itatiaia_fixintelipost/zipcode_range_cd_eletro');
        $zipcodeCdEletro = $config->getValue('carriers/itatiaia_fixintelipost/zipcode_cd_eletro');
        $attributeSetIdEletro = $config->getValue('carriers/itatiaia_fixintelipost/attribute_set_id_eletro');

        $zipcode = $defaultZipcode;
        $has_material_aco = false;
        $has_eletro = false;
        $has_not_eletro = false;

        foreach($items as $item){
            $product = $objectManager->get('\Magento\Catalog\Model\ProductFactory')->create()->load($item->getProduct()->getId());
            $material = $product->getAttributeText('material_predominante');
            if($material && ($material == 'Aço' || $material == 'aço')){
                $has_material_aco = true;
            }
            // $logger->info('ATTRIBUTE_SET', [$product->getAttributeSetId()]);

            if($product->getAttributeSetId() == $attributeSetIdEletro){
                $has_eletro = true;
            }
            if($product->getAttributeSetId() != $attributeSetIdEletro){
                $has_not_eletro = true;
            }
        }

        if($has_material_aco){
            $zipcode = $zipcodeWarehouseAco;
        }

        if($has_eletro && !$has_not_eletro){
            // se somente houver eletro no carrinho, usar o CD de origem custom
            // $logger->info('SOMENTE ELETRO', [$destPostcode, $zipcodeRangeEletro]);

            if($this->isZipcodeInRange($destPostcode, $zipcodeRangeEletro)){
                // $logger->info('IN RANGE');
                $zipcode = $zipcodeCdEletro;
            }
        }

        // $logger->info('ORIGEM', [$zipcode]);

        return $zipcode;
    }

    public function isZipcodeInRange($postcode, $string_range)
    {
        $postcode = (int)preg_replace('/\D+/','',$postcode);
        $postcode_range = $string_range;

        if(!$postcode_range) return false;

        $ranges = explode(';', $postcode_range);
        $inRange = false;
        foreach($ranges as $range){
            $parts = explode('-',$range);
            if(isset($parts[0]) && isset($parts[1])){
                $from = (int)$parts[0];
                $to = (int)$parts[1];
                if($postcode >= $from && $postcode <= $to){
                    $inRange = true;
                }
            }
        }
        return $inRange;
    }

    public function updateIntelipostInvoice($order_id)
    {
        // [x] resgatar pedido, somente quando o status for faturado_sap
        // [x] verificar se possui invoice
        // [x] consultar nos comentários se o status foi alterado para faturado_sap
        // [x] resgatar corpo do comentário e fazer parse para dados da Intelipost
        // [x] resgatar ID de fatura gerada para o pedido
        // [x] verificar se não existe um intelipost_invoice inserido
        // [x] inserir dados parseados na tabela intelipost_invoice

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $order = $objectManager->get('\Magento\Sales\Model\OrderFactory')->create()->load($order_id);

        $logger = $objectManager->get('\Psr\Log\LoggerInterface');
        $logger->info('HELPER UPDATE INVOICE');

        // somente na troca de um status para outro será disparado
        if(($order->getState() == 'processing' || $order->getState() == 'complete') && $order->getStatus() == 'faturado_sap'){

            $logger->info('ORDER DATA POST', [
                'id' => $order->getId(),
                'state' => $order->getState(),
                'status' => $order->getStatus()
            ]);

            $status_histories = $order->getStatusHistories();
            $last_status = null;
            foreach($status_histories as $status){
                if(!$last_status){
                    $last_status = $status;
                }
            }

            $nf_data = $last_status->getComment();

            if(strpos($nf_data, 'invoice_number') !== false){
                $nf_data = $this->parseNfData($nf_data);
                $logger->info('ORDER NF DATA', $nf_data);

                if(isset($nf_data['invoice_number'])){
                    $invoices = $order->getInvoiceCollection();
                    $last_paid_invoice = null;
                    foreach($invoices as $invoice){
                        if($invoice->getState() == \Magento\Sales\Model\Order\Invoice::STATE_PAID){
                            $last_paid_invoice = $invoice;
                            $logger->info('ORDER INVOICE PAID DATA', [$last_paid_invoice->getData()]);
                        }
                    }

                    if(!$this->intelipostInvoiceExists($nf_data['invoice_number'], $nf_data['order_number'])){
                        $nf_number = ltrim($nf_data['invoice_number'], '0');

                        $intelipostInvoice = $objectManager->get('\Intelipost\Push\Model\InvoiceFactory')->create();
                        $intelipostInvoice->setInvoiceNumber($nf_number);
                        $intelipostInvoice->setOrderNumber($nf_data['order_number']);
                        $intelipostInvoice->setInvoiceSeries($nf_data['invoice_series']);
                        $intelipostInvoice->setInvoiceKey($nf_data['invoice_key']);
                        $intelipostInvoice->setInvoiceDate(date('Y-m-d H:i:s', strtotime($nf_data['invoice_date'])));
                        $intelipostInvoice->setInvoiceTotalValue($nf_data['invoice_total_value']);
                        $intelipostInvoice->setInvoiceProductsValue($nf_data['invoice_products_value']);
                        $intelipostInvoice->setInvoiceCfop($nf_data['invoice_cfop']);
                        $intelipostInvoice->save();

                        $logger->info('INTELIPOST_INVOICE CREATED', $intelipostInvoice->getData());

                        $this->createMagentoShipment($order);
                    }
                }
            }
        }
    }

    public function parseNfData($string)
    {
        $parts = explode('|', $string);
        $data = [];
        foreach($parts as $item){
            $kv = explode(':', $item);
            if($kv[0] == 'invoice_date'){
                $data[$kv[0]] = implode(':', array_slice($kv, 1));
            } else {
                $data[$kv[0]] = $kv[1];
            }
        }
        return $data;
    }

    public function intelipostInvoiceExists($invoice_number, $order_number)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $logger = $objectManager->get('\Psr\Log\LoggerInterface');

        $intelipostInvoiceCollection = $objectManager->get('\Intelipost\Push\Model\Resource\Invoice\CollectionFactory')->create();
        $intelipostInvoiceCollection->addFieldToFilter('invoice_number', $invoice_number);
        $intelipostInvoiceCollection->addFieldToFilter('order_number', $order_number);
        $intelipostInvoiceCollection->load();

        return $intelipostInvoiceCollection->count();
    }

    public function createMagentoShipment($order)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $logger = $objectManager->get('\Psr\Log\LoggerInterface');

        if($order->canShip()){
            $convertOrder = $objectManager->create('Magento\Sales\Model\Convert\Order');
            $shipment = $convertOrder->toShipment($order);
            foreach ($order->getAllItems() AS $orderItem) {
                if (! $orderItem->getQtyToShip() || $orderItem->getIsVirtual()) {
                    continue;
                }
                $qtyShipped = $orderItem->getQtyToShip();
                $shipmentItem = $convertOrder->itemToShipmentItem($orderItem)->setQty($qtyShipped);
                $shipment->addItem($shipmentItem);
            }

            $shipment->register();
            $shipment->getOrder()->setIsInProcess(true);
            $shipment->getOrder()->setStatus('complete');

            try {
                // Save created shipment and order
                $shipment->save();
                $shipment->getOrder()->save();

                // Send email
                // $_objectManager->create('Magento\Shipping\Model\ShipmentNotifier')
                //     ->notify($shipment);

                // $shipment->save();
            } catch (\Exception $e) {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __($e->getMessage())
                );
            }
        } else {
            $logger->critical('Pedido não pode gerar entrega', [
                'id' => $order->getId(),
                'increment_id' => $order->getIncrementId()
            ]);
        }
    }

    public function getMaxLeadTime($items)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $leadTimes = [];
        foreach ($items as $item)
        {
            if($grouped = $objectManager->get('\Itatiaia\Custom\Helper\Data')->getGroupedByQuoteItem($item)){
                $product = $objectManager->get('\Magento\Catalog\Model\ProductFactory')->create()->load($grouped->getId());
            } else {
                $product = $objectManager->get('\Magento\Catalog\Model\ProductFactory')->create()->load($item->getProduct()->getId());
            }
            if($product->getLeadTime())
            {
                $leadTimes[] = $product->getLeadTime();
            }
        }
        if(count($leadTimes)){
            return max($leadTimes);
        }
        return 0;
    }
}
