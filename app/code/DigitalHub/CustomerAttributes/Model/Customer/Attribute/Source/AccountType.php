<?php
namespace DigitalHub\CustomerAttributes\Model\Customer\Attribute\Source;

class AccountType extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

    /**
     * getAllOptions
     *
     * @return array
     */
    public function getAllOptions()
    {
        if ($this->_options === null) {
            $this->_options = [
                ['value' => 'pf', 'label' => __('Pessoa Física')],
                ['value' => 'pj', 'label' => __('Pessoa Jurídica')]
            ];
        }
        return $this->_options;
    }
}
