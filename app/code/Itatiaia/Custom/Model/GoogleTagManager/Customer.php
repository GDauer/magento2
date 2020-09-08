<?php
namespace Itatiaia\Custom\Model\GoogleTagManager;

class Customer extends \MagePal\GoogleTagManager\Model\Customer
{
    /**
     * Get Customer array
     *
     * @return array
     */
    public function getCustomer()
    {
        if ($this->_customerSession->isLoggedIn()) {
            return [
                'isLoggedIn' => $this->_customerSession->isLoggedIn(),
                'id' => $this->_customerSession->getCustomerId(),
                'groupId' => $this->_customerSession->getCustomerGroupId(),
                'email' => $this->_customerSession->getCustomer()->getEmail(),
            ];
        } else {
            return [
                'isLoggedIn' => $this->_customerSession->isLoggedIn(),
            ];
        }
    }
}
