<?php

/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2021 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 * @link        http://www.webjump.com.br
 */

namespace Itatiaia\FixIntelipost\Helper;

class Api extends \Intelipost\Quote\Helper\Api
{

    /**
     * Magento ScopeConfigInterface
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;
    
    /**
     * Magento CacheInterface
     *
     * @var \Magento\Framework\App\CacheInterface
     */
    protected $_cache;

    /**
     * Magento DirectoryList
     *
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $_directoryList;

    /**
     * Itatiaia Logging instance
     *
     * @var \Itatiaia\FixIntelipost\Logger\Logger
     */
    protected $_ipLogger;

    const QUOTE_BY_PRODUCT = 'quote_by_product/';
    const QUOTE_BUSINESS_DAYS = 'quote/business_days/';
    const QUOTE_AVAILABLE_SCHEDULING_DATES = 'quote/available_scheduling_dates/';

    public function __construct
    (
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\CacheInterface $cache,
        \Itatiaia\FixIntelipost\Logger\Logger $logger,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Psr\Log\LoggerInterface $loggerPsr
    )
    {
        // parent::__construct($scopeConfig, $cache, $loggerPsr);
        $this->_scopeConfig = $scopeConfig;
        $this->_cache = $cache;
        $this->_ipLogger = $logger;
        $this->_directoryList = $directoryList;
    }

    public function log($message, $name = "intelipost", $array = null)
    {
        if (!is_null($array))
        {
            $message .= " - " . json_encode($array);
        }

        $this->_ipLogger->setName($name);
        $this->_ipLogger->debug($message);
    }

    public function quoteRequest ($httpMethod, $apiMethod, & $postData = false)
    {
        $postData ['api_request'] = json_encode ($postData);
        $response = $this->apiRequest ($httpMethod, $apiMethod, json_encode ($postData));

        $result = json_decode ($response, true);

        if (!$result)
        {
            $result = $this->getContingencyValues ($postData);
            $postData ['api_response'] = json_encode ($result, true);
            return $result;
        }

        if (!strcmp ($result ['status'], 'ERROR'))
        {
            $messages = null;
            foreach ($result ['messages'] as $_message)
            {
                $messages .= $_message ['text'];
            }

            $this->log($postData['destination_zip_code'] .' : ' .$messages);
            throw new \Exception ($messages);
        }

        $postData ['api_response'] = $response;
        return $result;
    }

    public function getCacheIdentifier ($postData)
    {
        $identifier = 'intelipost_api_'
            . $postData ['destination_zip_code'] . '_'
            . $postData ['cart_weight'] . '_'
            . $postData ['cart_amount'] . '_'
            . $postData ['cart_qtys'];

        return $identifier;
    }

    public function getCache ($postData)
    {
        $identifier = $this->getCacheIdentifier ($postData);
        $result = unserialize ($this->_cache->load ($identifier));

        return $result;
    }

    public function saveCache ($postData, $responseData)
    {
        $identifier = $this->getCacheIdentifier ($postData);
        $lifetime = intval ($this->_scopeConfig->getValue ('carriers/intelipost/cache_exp_time'));
        $result = $this->_cache->save (serialize ($responseData), $identifier, array('collections'), $lifetime);

        return $result;
    }

    public function getContingencyValues ($postData)
    {
        $destZipcode = intval ($postData ['destination_zip_code']);
        $varPath = $this->_directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR);
        $intelipostVarPath = $varPath . DIRECTORY_SEPARATOR . 'intelipost';

        /*
        * calculate State Codification
        */
        $stateFile = $intelipostVarPath . DIRECTORY_SEPARATOR . 'state_codification.json';
        $stateContent = json_decode (file_get_contents ($stateFile), true);

        $stateCode = null;
        $stateType = null;

        foreach ($stateContent [0] as $beginZip => $child)
        {
            if ($destZipcode >= $beginZip && $destZipcode <= $child ['cep_end'])
            {
                $stateCode = strtoupper ($child ['state']);
                $stateType = strtoupper ($child ['type']);

                break;
            }
        }

        /*
        * calculate Contingency Table
        */
        $contingencyTable = $this->_scopeConfig->getValue ('carriers/intelipost/contingency_table');
        $tableFile = $intelipostVarPath . DIRECTORY_SEPARATOR . $contingencyTable;

        $tableContent = json_decode (file_get_contents ($tableFile), true);

        $totalWeight = 0;
        foreach ($postData ['products'] as $product)
        {
            $totalWeight += intval ($product ['weight']);
        }

        $delivery = array(
            'delivery_method_id'     => 'fallback',
            'delivery_method_type'   => 'fallback',
            'provider_shipping_cost' => 0,
        );

        foreach ($tableContent as $stateId => $stateContent)
        {
            if (!strcmp (strtoupper ($stateId), $stateCode))
            {
                foreach ($stateContent as $regionId => $regionContent)
                {
                    if (!strcmp (strtoupper ($regionId), $stateType))
                    {
                        $delivery ['delivery_estimate_business_days'] = $regionContent ['delivery_estimate_business_days'];

                        foreach ($regionContent ['final_shipping_cost'] as $weight => $price)
                        {
                            if ($totalWeight <= $weight)
                            {
                                $delivery ['final_shipping_cost'] = $price;
                                $delivery ['logistic_provider_name'] = $regionId;
                                $delivery ['description'] = $regionId;
                                $delivery ['delivery_method_name'] = $regionId;

                                break;
                            }
                        }
                    }
                }
            }
        }

        $result = array(
            'content' => array(
                'id' => 0,
                'delivery_options' => array ($delivery)
            )
        );

        return $result;
    }

    public function getEstimateDeliveryDate ($originZipcode, $destPostcode, $businessDays)
    {
        $response = $this->apiRequest (self::GET, self::QUOTE_BUSINESS_DAYS
            . "{$originZipcode}/{$destPostcode}/{$businessDays}");

        $result = json_decode ($response, true);

        return $result;
    }

    public function getAvailableSchedulingDates ($originZipcode, $destPostcode, $deliveryMethodId)
    {
        $response = $this->apiRequest (self::GET, self::QUOTE_AVAILABLE_SCHEDULING_DATES
            . "{$deliveryMethodId}/{$originZipcode}/{$destPostcode}");

        $result = json_decode ($response, true);

        return $result;
    }
}
