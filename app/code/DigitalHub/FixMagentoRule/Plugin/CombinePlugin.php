<?php

namespace DigitalHub\FixMagentoRule\Plugin;

use Magento\Rule\Model\Condition\Combine;
use Psr\Log\LoggerInterface;

/**
 * Class CombinePlugin
 * @package DigitalHub\FixMagentoRule\Plugin
 */
class CombinePlugin
{
    /** @var LoggerInterface */
    protected $logger;

    /**
     * CombinePlugin constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param Combine $subject
     * @param array | null $arr
     * @param string $key
     * @return array
     */
    public function beforeLoadArray(Combine $subject, $arr, $key = 'conditions')
    {
        if (isset($arr['conditions']) && $arr['conditions']) {
            $arr['conditions'] = $this->removeSpacesFromStringValues($arr['conditions']);
        }

        return [$arr, $key];
    }

    /**
     * Remove white spaces and tabulations from string value
     * @param $conditions
     * @return mixed
     */
    private function removeSpacesFromStringValues($conditions)
    {
        foreach ($conditions as $key => $condition) {
            try {
                if ((!isset($condition['value']) || !is_string($condition['value'])) && (isset($condition['attribute']) && $condition['attribute'] !== 'sku')) {
                    continue;
                }
                $conditions[$key]['value'] = preg_replace('/\s+/', '', $condition['value']);
            } catch (\Exception $e) {
                $this->logger->error(__('Error trying to remove white space from string on Combined Plugin. Error: %1',
                    $e->getMessage()));
            }
        }

        return $conditions;
    }
}