<?php
namespace Itatiaia\Custom\Plugin\Framework\Reflection;

class DataObjectProcessorPlugin
{
    public function afterBuildOutputDataArray(\Magento\Framework\Reflection\DataObjectProcessor $subject, $result, $dataObject, $dataObjectType)
    {
        $origDataObject = $dataObject;
        $origDataObjectType = $dataObjectType;

        $data = $result;

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
