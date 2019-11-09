<?php

declare(strict_types=1);

namespace Treinamento\PluginExample\Plugin\frontend\App\Action;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\RequestInterface;
use Psr\Log\LoggerInterface;

/**
 * Class ActionPlugin
 *
 * @package Treinamento\PluginExample\Plugin\frontend\App\Action
 */
class ActionPluginThirty
{
    /** @var LoggerInterface $logger */
    private $logger;

    /**
     * ActionPlugin constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }


    /**
     * @param Action $subject
     * @param mixed $result
     *
     * @return mixed
     */
    public function afterDispatch(Action $subject, $result)
    {
        $this->logger->debug('SortOrder 30, Action: ' . $subject->getRequest()->getFullActionName());
        $this->logger->debug('After Plugin: ' . get_class($subject) . PHP_EOL);

        return $result;
    }

    /**
     * @param Action $subject
     *
     * @return null
     */
    public function beforeDispatch(Action $subject)
    {
        $this->logger->debug('SortOrder 30, Action: ' . $subject->getRequest()->getFullActionName());
        $this->logger->debug('Before Plugin: ' . get_class($subject) . PHP_EOL);

        return null;
    }

    /**
     * @param Action $subject
     * @param callable $proceed
     * @param RequestInterface $request
     *
     * @return mixed
     */
    public function aroundDispatch(Action $subject, callable $proceed, RequestInterface $request)
    {
        $this->logger->debug('SortOrder 30, Action: ' . $subject->getRequest()->getFullActionName());
        $this->logger->debug('Around[First Dispatch] Plugin: ' . get_class($subject) . PHP_EOL);

        $return = $proceed($request);

        $this->logger->debug('SortOrder 30, Action: ' . $subject->getRequest()->getFullActionName());
        $this->logger->debug('Around[Second Dispatch] Plugin: ' . get_class($subject) . PHP_EOL);

        return $return;
    }
}
