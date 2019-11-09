<?php

declare(strict_types=1);

namespace Treinamento\ServiceContractsExample\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\Action;
use Magento\Customer\Api\CustomerRepositoryInterface;

/**
 * Class Index
 *
 * @package Treinamento\ServiceContractsExample\Controller\Index
 */
class Index extends Action implements HttpGetActionInterface
{
    /** @var CustomerRepositoryInterface */
    private $customerRepository;

    /**
     * Index constructor.
     *
     * @param Context $context
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        Context $context,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->customerRepository = $customerRepository;
        parent::__construct($context);
    }

    /**
     * {@inheritDoc}
     */
    public function execute()
    {
        $resultPage   = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        try {
            /** @var \Magento\Customer\Api\Data\CustomerInterface $customer */
            $customer = $this->customerRepository->getById(1);

            $customer->setFirstname(uniqid('Test_Repository__'));
            $this->customerRepository->save($customer);

        } catch (\Exception $exception) {
            var_dump('ERROR: ' . $exception->getMessage());
        }

        $resultPage->setData($customer->getFirstname());
        return $resultPage;
    }
}
