<?php
namespace Itatiaia\Custom\Plugin\Framework\Reflection;

class DataObjectProcessorPlugin
{
    public function afterBuildOutputDataArray(\Magento\Framework\Reflection\DataObjectProcessor $subject, $result, $dataObject, $dataObjectType)
    {
        $origDataObject = $dataObject;
        $origDataObjectType = $dataObjectType;

        $data = $result;

        if(isset($data['customer_group_id']) && $dataObject->getBillingAddress())
        {
            $this->setCustomerName($dataObject, $data);
        }
        
        if(isset($data['additional_information'])){
            $data['additional_information'] = $dataObject->getAdditionalInformation();
        }

        return $data;
    }

    private function setCustomerName($object, &$data)
    {
        if(!isset($data['customer_firstname']))
        {
            $data['customer_firstname'] = $object->getBillingAddress()->getFirstname();
            $data['customer_lastname'] = $object->getBillingAddress()->getLastname();
        }
    }
}
