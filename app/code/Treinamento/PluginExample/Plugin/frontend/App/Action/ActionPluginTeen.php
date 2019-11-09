<?php

declare(strict_types=1);

namespace Treinamento\PluginExample\Plugin\frontend\App\Action;

use Magento\Framework\App\Action\Action;
use Psr\Log\LoggerInterface;

/**
 * Class ActionPlugin
 *
 * @package Treinamento\PluginExample\Plugin\frontend\App\Action
 */
class ActionPluginTeen
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
     *
     * @return null
     */
    public function beforeDispatch(Action $subject)
    {
        $this->logger->debug('SortOrder 10, Action: ' . $subject->getRequest()->getFullActionName());
        $this->logger->debug('Before Plugin: ' . get_class($subject) . PHP_EOL);

        return null;
    }

    /**
     * @param Action $subject
     * @param mixed $result
     *
     * @return mixed
     */
    public function afterDispatch(Action $subject, $result)
    {
        $this->logger->debug('SortOrder 10, Action: ' . $subject->getRequest()->getFullActionName());
        $this->logger->debug('After Plugin: ' . get_class($subject) . PHP_EOL);

        return $result;
    }
}
