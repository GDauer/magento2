<?php
declare(strict_types=1);

namespace Treinamento\CronExample\Cron;

use Psr\Log\LoggerInterface as Logger;

class CronLogger
{
    /** @var Logger */
    private $logger;

    /**
     * CronLogger constructor.
     *
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return void
     */
    public function execute()
    {
        $this->logger->debug('CRON EXAMPLE');
    }
}
