<?php

declare(strict_types=1);

namespace Treinamento\AdminPageExample\ViewModel;

use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\StoresConfig;

/**
 * Class Argument
 *
 * @package Treinamento\AdminPageExample\ViewModel
 */
class Argument implements ArgumentInterface
{
    const PATH = 'teste';

    /** @var StoresConfig */
    private $storeConfig;

    /** @var EncryptorInterface */
    private $decryptor;

    /**
     * Argument constructor.
     *
     * @param StoresConfig $storesConfig
     * @param EncryptorInterface $decryptor
     */
    public function __construct(StoresConfig $storesConfig, EncryptorInterface $decryptor)
    {
        $this->storeConfig = $storesConfig;
        $this->decryptor = $decryptor;
    }

    public function getValues()
    {
        return $this->getValuesSplitedArrayWithValues();
    }

    /**
     * @return array
     */
    private function getValuesSplitedArrayWithValues()
    {
        $data = $this->getValuesFromConfigData();

        foreach ($data as $items) {
            $return[] = $this->splitItemsWithValues($items);
        }
        return $return;
    }

    /**
     * @return array
     */
    private function getValuesFromConfigData()
    {
        return $this->storeConfig->getStoresConfigByPath(self::PATH);
    }

    /**
     * @param array $items
     * @return array
     */
    private function splitItemsWithValues(array $items)
    {
        foreach ($items as $key => $item) {
            if ($key === 'password') {
                $return[] = $this->decryptor->decrypt($item['password']);
            } else {
                $return[] = $item;
            }
        }
        return $return;
    }
}
