<?php


namespace DigitalHub\Validation\Observer;


use DigitalHub\Validation\Common\AddressValidator;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Magento\Framework\Event\Observer;

class AddressSaveBeforeValidate implements ObserverInterface
{
    /**
     * @var AddressValidator
     */
    private $addressValidator;

    /**
     * CheckoutSubmitBeforeAddressValidationObserver constructor.
     * @param AddressValidator $addressValidator
     */
    public function __construct(AddressValidator $addressValidator)
    {
        $this->addressValidator = $addressValidator;
    }

    public function execute(Observer $observer)
    {
        $address = $observer->getCustomerAddress();
        $validation = $this->addressValidator->isValid($address);
        if (!isset($validation['isValid']) || $validation['isValid'] === false) {
            $message = 'Dados de endereço inválidos';

            if (!empty($validation['message'])) {
                $message .= ': ' . $validation['message'];
            }

            throw new LocalizedException(new Phrase($message));;
        }
    }
}
