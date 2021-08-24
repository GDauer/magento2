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
use Magento\Customer\Model\Session;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;

class CustomerValidations implements \Magento\Framework\Event\ObserverInterface
{
    protected $_request;
    protected $_helper;
    protected $_session;
    protected $_customerCollectionFactory;


    
    public function __construct(
        ManagerInterface $messageManager,
        RequestInterface $request,
        Session $session,
        CollectionFactory  $customerCollectionFactory
    ) {
        $this->_request = $request;
        $this->_session = $session;
        $this->_customerCollectionFactory = $customerCollectionFactory;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {

        $customer = $observer->getCustomer();
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
         if($this->_hasTaxvat($customer, $cpf)){
            throw new CouldNotSaveException(
                __("%1 is invalid!", "CPF")
             ); 
         }



    }
    
    protected function _hasTaxvat( $customer, $value){

        $customerCollection = $this->_customerCollectionFactory->create();
        $customerCollection->addAttribuTeToFilter('taxvat', $value);

        if ($customer->getId())
            $customerCollection->addAttribuTeToFilter('entity_id', array('neq' => (int)$customer->getId()));


        if ($customerCollection->getSize() > 0) 
            return  true;
    

        return false;
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
