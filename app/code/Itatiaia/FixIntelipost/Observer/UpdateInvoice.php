<?php
namespace Itatiaia\FixIntelipost\Observer;

class UpdateInvoice implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $logger = $objectManager->get('\Psr\Log\LoggerInterface');
        $logger->info('UPDATE INVOICE');

        // [x] resgatar pedido, somente quando o status for faturado_sap
        // [x] verificar se possui invoice
        // [x] consultar nos comentários se o status foi alterado para faturado_sap
        // [x] resgatar corpo do comentário e fazer parse para dados da Intelipost
        // [x] resgatar ID de fatura gerada para o pedido
        // [x] verificar se não existe um intelipost_invoice inserido
        // [x] inserir dados parseados na tabela intelipost_invoice

        $order = $observer->getOrder();

        // $logger->info('ORDER DATA PRE', $order->getData());

        // somente na troca de um status para outro será disparado
        if($order->getState() == 'processing' && $order->getStatus() == 'faturado_sap'){
            $order = $objectManager->get('\Magento\Sales\Model\Order')->load($order->getId());

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
                    $intelipostInvoice = $objectManager->get('\Intelipost\Push\Model\InvoiceFactory')->create();
                    $intelipostInvoice->setInvoiceNumber($nf_data['invoice_number']);
                    $intelipostInvoice->setOrderNumber($nf_data['order_number']);
                    $intelipostInvoice->setInvoiceSeries($nf_data['invoice_series']);
                    $intelipostInvoice->setInvoiceKey($nf_data['invoice_key']);
                    $intelipostInvoice->setInvoiceDate(date('Y-m-d H:i:s', strtotime($nf_data['invoice_date'])));
                    $intelipostInvoice->setInvoiceTotalValue($nf_data['invoice_total_value']);
                    $intelipostInvoice->setInvoiceProductsValue($nf_data['invoice_products_value']);
                    $intelipostInvoice->setInvoiceCfop($nf_data['invoice_cfop']);
                    $intelipostInvoice->save();

                    $logger->info('INTELIPOST_INVOICE CREATED', $intelipostInvoice->getData());
                }
            }
        }
    }

    private function parseNfData($string)
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

    private function intelipostInvoiceExists($invoice_number, $order_number)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $logger = $objectManager->get('\Psr\Log\LoggerInterface');

        $intelipostInvoiceCollection = $objectManager->get('\Intelipost\Push\Model\Resource\Invoice\CollectionFactory')->create();
        $intelipostInvoiceCollection->addFieldToFilter('invoice_number', $invoice_number);
        $intelipostInvoiceCollection->addFieldToFilter('order_number', $order_number);
        $intelipostInvoiceCollection->load();

        return $intelipostInvoiceCollection->count();
    }
}
