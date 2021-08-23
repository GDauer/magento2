<?php

/**
 * Copyright © Itatiaia All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);


namespace Itatiaia\CustomerValidate\Observer;

use \Magento\Framework\Message\ManagerInterface;
use \Magento\Framework\App\RequestInterface;
use \Magento\Framework\Exception\CouldNotSaveException;
use \Magento\Customer\Model\Session;
use \Magento\Customer\Model\CustomerFactory;


class CustomerValidations implements \Magento\Framework\Event\ObserverInterface
{
    private $_request;
    private $_helper;
    private $_session;
    private $customerFactory;

    public function __construct(
        ManagerInterface $messageManager,
        RequestInterface $request,
        Session $session,
        CustomerFactory  $customerFactory
    ) {
        $this->_request = $request;
        $this->_session = $session;
        $this->customerFactory = $customerFactory;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $cpf = $this->_request->getParam('taxvat');
        if (!isset($cpf)) {
            throw new CouldNotSaveException(
                __("%1 is required.", "CPF")
            );
        }

        //Valid CPF
        if(!$this->_validateTaxvat($cpf)){
            throw new CouldNotSaveException(
                __("%1 is invalid", "Error")
            );
        }    

        //Valid Chinese character
        $name =  $this->_request->getParam('firstname');
        if( preg_match("/\p{Han}+/u", $name)){
            throw new CouldNotSaveException(
                __("%1 is invalid", "Error")
            );
        }

         //Valid Chinese character
         $lastname =  $this->_request->getParam('lastname');
         if( preg_match("/\p{Han}+/u", $lastname)){
             throw new CouldNotSaveException(
                __("%1 is invalid!", "Error")
             );
         }

         //validate Taxvat is Unique
         if(!$this->_hasTaxvat('taxvat', $cpf)){
            throw new CouldNotSaveException(
                __("%1 is invalid!", "Error")
             ); 
         }

    }
    
    protected function _hasTaxvat($fieldName, $value){
        if($this->_session->getCustomer()->getId()!="" && $this->_session->getCustomer()->getData($fieldName) == $value)
            return true;
                 
       return $this->_getFilteredCustomersCollection($fieldName, $value);
    }


    protected function _getFilteredCustomersCollection($fieldName , $value) {
        $customerCollection = $this->_customerFactory->create()->getCollection()
                ->addAttributeToSelect("*")
                ->addAttributeToFilter($fieldName, $value);

                foreach ($customerCollection as $customer){
                    if($customer->getCreatedAt())
                        return true;
                    
                    return false;
                }     
                    
         return true;   
    }

    protected function _validateTaxvat($cpf)
    {

        $cpf = preg_replace('/[^0-9]/is', '', $cpf);

        if (strlen($cpf) != 11) 
            return false;
    

        if (preg_match('/(\d)\1{10}/', $cpf)) 
            return false;
        
       
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf{$c} * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf{$c} != $d) {
                return false;
            }
        }

        return true;
    }

    
}
