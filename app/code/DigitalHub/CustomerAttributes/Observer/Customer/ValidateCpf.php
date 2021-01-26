<?php
namespace DigitalHub\CustomerAttributes\Observer\Customer;

use \Magento\Framework\Phrase;

class ValidateCpf implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        $customer = $observer->getCustomer();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $helper = $objectManager->get('\DigitalHub\CustomerAttributes\Helper\Data');

        if(!$helper->isValidCpf($customer->getTaxvat()) && $customer->getAccountType() == 'pf'){
            throw new \Magento\Framework\Exception\LocalizedException(new Phrase('O CPF informado é inválido.'));
        }

        if(!$helper->isValidCnpj($customer->getTaxvat()) && $customer->getAccountType() == 'pj'){
            throw new \Magento\Framework\Exception\LocalizedException(new Phrase('O CNPJ informado é inválido.'));
        }
    }
}
