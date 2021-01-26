<?php

namespace Itatiaia\AdyenCustom\Plugin\Helper;

class Requests
{

    public function afterBuildCustomerIpData(\Adyen\Payment\Helper\Requests $subject, $request)
    {

        $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $request['shopperIP'];

        if(count(explode(',', $ip)) > 1){
            $ip = trim(explode(',', $ip)[0]);
        }

        $request['shopperIP'] = $ip;

        return $request;
    }
}