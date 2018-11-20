<?php

namespace DigitalHub\MundipaggTemp\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer as EventObserver;
use MundiPagg\MundiPagg\Helper\CustomerUpdateMundipaggHelper;

class AdminCustomerBeforeSave extends \MundiPagg\MundiPagg\Observer\AdminCustomerBeforeSave
{
    protected $customerUpdateMundipaggHelper;

    /**
     * AdminCustomerSaveAfter constructor.
     */
    public function __construct(
        CustomerUpdateMundipaggHelper $customerUpdateMundipaggHelper
    ) {
        $this->customerUpdateMundipaggHelper = $customerUpdateMundipaggHelper;
    }

    /**
     * @param EventObserver $observer
     * @return void
     */
    public function execute(EventObserver $observer)
    {
        // $event = $observer->getEvent();
        //
        // $this->customerUpdateMundipaggHelper->updateEmailMundipagg($event->getCustomer());

    }

}
