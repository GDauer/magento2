<?php

declare(strict_types=1);

namespace Treinamento\ObserverExample\Observer\Controller\Action;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
use Psr\Log\LoggerInterface;

class PredispatchObserver implements ObserverInterface
{
    /** @var LoggerInterface */
    private $logger;

    /**
     * PredispatchObserver constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        /** @var RequestInterface $request */
        $request = $observer->getEvent()->getRequest();
        $name = $request->getFullActionName();

        $this->logger->debug('OBSERVER FULL ACTION NAME: ' . $name);
    }
}
