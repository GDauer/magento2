<?php
namespace Itatiaia\Custom\Plugin\Framework\Reflection;

class DataObjectProcessorPlugin
{
    public function afterBuildOutputDataArray(\Magento\Framework\Reflection\DataObjectProcessor $subject, $result, $dataObject, $dataObjectType)
    {
        $origDataObject = $dataObject;
        $origDataObjectType = $dataObjectType;

        $data = $result;

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $scopeConfig = $objectManager->get('\Magento\Framework\App\Config\ScopeConfigInterface');
        $active = $scopeConfig->getValue('carriers/itatiaia_intelipost_mapping/active');

        $data['shipping_method_sap'] = null;

        if((int)$active){
            $mapping = $rules = json_decode($scopeConfig->getValue('carriers/itatiaia_intelipost_mapping/mapping'));

            if(isset($data['extension_attributes'])){

                if(isset($data['extension_attributes']['intelipost_quote'])){
                    $intelipost_quote = json_decode($data['extension_attributes']['intelipost_quote']);
                    $delivery_method_name = $intelipost_quote->result->delivery_method_name;

                    // $objectManager->get('\Psr\Log\LoggerInterface')->info('PLUGIN', [$delivery_method_name]);

                    foreach($rules as $rowId => $rule){
                        if($rule->intelipost == $delivery_method_name){
                            // $data['shipping_method_sap'] = $rule->sap;
                            $data['shipping_description'] = $rule->sap;
                        }
                    }
                }
            }
        }

        if(isset($data['customer_group_id'])){ // para assegurar que é um Order
            if($dataObject->getBillingAddress()){
                if(!isset($data['customer_firstname'])){
                    $data['customer_firstname'] = $dataObject->getBillingAddress()->getFirstname();
                    $data['customer_lastname'] = $dataObject->getBillingAddress()->getLastname();
                }
            }
        }

        return $data;
    }
}
