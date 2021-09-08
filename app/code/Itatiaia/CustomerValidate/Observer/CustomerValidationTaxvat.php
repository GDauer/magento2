<?php

/**
 * Copyright © Itatiaia All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);


namespace Itatiaia\CustomerValidate\Observer;

use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Customer\Model\CustomerFactory;

class CustomerValidationTaxvat implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    private $_messageManage;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $_request;

    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    private $_customerCollectionFactory;


    /**
     * ValidateTaxvat constructor.
     * @param ManagerInterface $messageManager
     * @param RequestInterface $request
     * @param Collection $customerCollectionFactory
     * 
     */
    public function __construct(
        ManagerInterface $messageManager,
        RequestInterface $request,
        CustomerFactory $customerCollectionFactory
    ) {
        $this->_request = $request;
        $this->_customerCollectionFactory = $customerCollectionFactory;
        $this->_messageManage = $messageManager;
    }


    /**
     * Validation method
     * @param Observer $observer
     * @throws \Magento\Framework\Exception\LocalizedException
     */

    public function execute(\Magento\Framework\Event\Observer $observer)
    {

        $customer = $observer->getCustomer();
        $cpf = $this->_request->getParam('taxvat');

        if (isset($cpf)) {
            //Valid CPF
            if (!$this->_validateTaxvat($cpf)) {
                throw new CouldNotSaveException(
                    __("%1 is invalid", "Error")
                );
            }

            // //validate Taxvat is Unique
            // if ($this->_hasTaxvat($customer, $cpf)) {
            //     throw new CouldNotSaveException(
            //         __("%1 já cadastrado !", "CPF")
            //     );
            // }
        }

    }


    /**
     * Validate has Taxvat
     * @param $customer
     * @param $value
     * @return bool
     */
    private function _hasTaxvat($customer, $value): bool
    {
        $customerCollection = $this->_customerCollectionFactory->create()->getCollection();
        $customerCollection->addAttribuTeToFilter('taxvat', $value);

        if ($customer)
            $customerCollection->addAttribuTeToFilter('entity_id', array('neq' => (int) $customer->getId()));


        if ($customerCollection->getSize() > 0)
            return  true;


        return false;
    }

    /**
     * Validate Taxvat Number
     * @param $cpf
     * @return  boll
     */
    private function _validateTaxvat($cpf): bool
    {

        $cpf = preg_replace('/[^0-9]/is', '', $cpf);

        if (strlen($cpf) != 11)
            return false;


        if (preg_match('/(\d)\1{10}/', $cpf))
            return false;


        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf{
                    $c} * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf{
                $c} != $d) {
                return false;
            }
        }

        return true;
    }
}
