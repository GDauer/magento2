<?php
namespace Itatiaia\Custom\Plugin\Framework\Reflection;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Json\Helper\Data;
use Intelipost\Quote\Model\Resource\Shipment\CollectionFactory;

class DataObjectProcessorPlugin
{
    /** @var ScopeConfigInterface */
    protected $scopeConfig;

    /** @var Data */
    protected $jsonHelper;

    /** @var CollectionFactory */
    protected $intelipostFactory;

    /**
     * DataObjectProcessorPlugin constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param Data $jsonHelper
     * @param CollectionFactory $intelipostFactory
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Data $jsonHelper,
        CollectionFactory $intelipostFactory
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->jsonHelper = $jsonHelper;
        $this->intelipostFactory = $intelipostFactory;
    }

    /**
     * @param \Magento\Framework\Reflection\DataObjectProcessor $subject
     * @param $result
     * @param $dataObject
     * @param $dataObjectType
     * @return mixed
     */
    public function afterBuildOutputDataArray(\Magento\Framework\Reflection\DataObjectProcessor $subject, $result, $dataObject, $dataObjectType)
    {
        $data = $result;

        if ($dataObjectType == '\Magento\Sales\Api\Data\OrderAddressInterface') {

            if (!isset($data['track_number'])) {
                $order = $dataObject->getOrder();
                if ($order != null) {
                    $orderNumber = $dataObject->getOrder()->getData('entity_id');
                    if (strlen($orderNumber) < 9) {
                        $orderNumber = str_pad($orderNumber, 9, "0", STR_PAD_LEFT);
                    }
                    $intelipostShipment = $this->intelipostFactory->create();
                    $intelipostShipment->addFieldToFilter('order_number', $orderNumber);
                    $intelipostShipment->addFieldToSelect('delivery_method_name');
                    $shipmentName = $intelipostShipment->getFirstItem()->getData('delivery_method_name');
                    $shipmentConfig = $this->jsonHelper->jsonDecode($this->scopeConfig->getValue('carriers/itatiaia_intelipost_mapping/mapping'));
                    foreach ($shipmentConfig as $transports) {
                        if ($transports['intelipost'] == $shipmentName) {
                            $data['shipping_method_sap'] = $transports['sap'];
                            break;
                        } else {
                            $data['shipping_method_sap'] = null;
                        }
                    }
                    if (isset($data['customer_group_id'])) { // para assegurar que é um Order
                        if ($dataObject->getBillingAddress()) {
                            if (!isset($data['customer_firstname'])) {
                                $data['customer_firstname'] = $dataObject->getBillingAddress()->getFirstname();
                                $data['customer_lastname'] = $dataObject->getBillingAddress()->getLastname();
                            }
                        }
                    }
                }
            }
        }

        return $data;
    }
}
