<?php


namespace DigitalHub\Validation\Plugin\Checkout;


use DigitalHub\Validation\Common\AddressValidator;
use Magento\Checkout\Model\GuestPaymentInformationManagement;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Api\Data\PaymentInterface;

class GuestPaymentInformationManagementPlugin
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

    /**
     * @param GuestPaymentInformationManagement $subject
     * @param $cartId
     * @param $email
     * @param PaymentInterface $paymentMethod
     * @param AddressInterface|null $billingAddress
     * @return array
     * @throws LocalizedException
     */
    public function beforeSavePaymentInformation(
        GuestPaymentInformationManagement $subject,
        $cartId,
        $email,
        PaymentInterface $paymentMethod,
        AddressInterface $billingAddress = null
    ) {
        $validation = $this->addressValidator->isValid($billingAddress);
        if (!isset($validation['isValid']) || $validation['isValid'] === false) {
            $message = 'Dados de endereço inválidos';
            if (!empty($billingAddress->getCustomerAddressId())) {
                $message = 'Por favor altere ou adicione um novo endereço, pois o endereço selecionado possui dados inválidos';
            } elseif (!empty($validation['message'])) {
                $message .= ': ' . $validation['message'];
            }

            throw new LocalizedException(new Phrase($message));
        }
        return [$cartId, $email, $paymentMethod, $billingAddress];
    }
}
