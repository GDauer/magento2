<?php
namespace Itatiaia\Custom\Model\Sales\Order;

class Config extends \Magento\Sales\Model\Order\Config
{
    protected function maskStatusForArea($area, $code)
    {
        /* CUSTOM CODE = REMOVE MAPPING */
        // if (isset($this->maskStatusesMapping[$area][$code])) {
        //     return $this->maskStatusesMapping[$area][$code];
        // }

        return $code;
    }
}
