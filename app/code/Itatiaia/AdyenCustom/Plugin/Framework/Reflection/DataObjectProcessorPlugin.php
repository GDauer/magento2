<?php
namespace Itatiaia\AdyenCustom\Plugin\Framework\Reflection;

class DataObjectProcessorPlugin
{
    public function afterBuildOutputDataArray(\Magento\Framework\Reflection\DataObjectProcessor $subject, $result, $dataObject, $dataObjectType)
    {
        $origDataObject = $dataObject;
        $origDataObjectType = $dataObjectType;

        $data = $result;

        if(isset($data['additional_information'])){
            $data['additional_information'] = $dataObject->getAdditionalInformation();
        }

        return $data;
    }
}
