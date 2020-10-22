<?php


namespace Itatiaia\Tracksale\Cron;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\Manager;
use Magento\Sales\Model\OrderFactory;
use Psr\Log\LoggerInterface;

class Track
{
    /**
     * @var OrderFactory
     */
    private $orderFactory;
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var Manager
     */
    private $eventManager;

    public function __construct
    (
        OrderFactory $orderFactory,
        ScopeConfigInterface $scopeConfig,
        LoggerInterface $logger,
        Manager $eventManager
    ){
        $this->orderFactory = $orderFactory;
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
        $this->eventManager = $eventManager;
    }

    public function execute()
    {
        $this->logger->info('ENTROU CRON: ', []);

        $orderCollection = $this->orderFactory->create()->getCollection()
            ->addFieldToFilter('state', 'complete')
//            ->addFieldToFilter('nps_sent', ['eq' => null])
            ->addFieldToFilter('created_at', ['gt' => '2019-06-05 15:00:00']);

            $api_key  = $this->scopeConfig->getValue('intelipost_basic/settings/api_key');

        foreach ($orderCollection as $order)
        {
            $nps = $order->getNpsSent();
            $increment = $order->getIncrementId();

                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://api.intelipost.com.br/api/v1/shipment_order/" . $order->getIncrementId(),
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                        "api-key:" . $api_key,
                        "cache-control: no-cache",
                        "platform: intelipost-docs",
                        "platform-version: v1.0.0",
                        "plugin: intelipost-plugin",
                        "plugin-version: v2.0.0",
                        "postman-token: 528c52f3-b344-59e0-56c2-9bac3766787e"
                    )
                ));

                $response = json_decode(curl_exec($curl));
                $err = curl_error($curl);

                if ($err) {
                    $this->logger->info('erro: ' , [$err]);
                } else if($response) {
                    if(isset($response->content)) {
                        if (strtoupper($response->content->shipment_order_volume_array[0]->shipment_order_volume_state) == 'DELIVERED') {
                            $order->setStatus('entregue');
                            $currentDate = date("Y-m-d");
                            $deliveredDate = date("Y-m-d", strtotime($response->content->shipment_order_volume_array[0]->delivered_date_iso));

                            if (!$order->getNpsSent()) {
                                $now = strtotime($currentDate);
                                $delivery = strtotime($deliveredDate);
                                $difference = $now - $delivery;
                                $differenceDays = round($difference / (60 * 60 * 24));

                                if ($differenceDays >= 7) {
//                                    $this->logger->info('$order->getIncrementId()', [$order->getIncrementId()]);
//                                    $this->logger->info('ENTROU ENTROU ENTROU ENTROU ENTROU ENTROU ENTROU ENTROU ENTROU ENTROU : ', [$err]);
                                    $this->eventManager->dispatch('itatiaia_tracksale_order_save_before', ['order' => $order]);
                                }
                            }

                            $order->save();
                            $this->eventManager->dispatch('itatiaia_tracksale_order_save_after', ['order' => $order]);

                        }
                    }
                }

                curl_close($curl);


        }
    }
}