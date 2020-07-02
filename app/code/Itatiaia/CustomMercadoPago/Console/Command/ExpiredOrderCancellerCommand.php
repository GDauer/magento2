<?php


namespace Itatiaia\CustomMercadoPago\Console\Command;

use Magento\Framework\App\State;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Itatiaia\CustomMercadoPago\Cron\Order\ExpiredOrderCanceller;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;

/**
 * Class ExpiredOrderCancellerCommand
 * @package Itatiaia\CustomMercadoPago\Console\Command
 */
class ExpiredOrderCancellerCommand extends Command
{
    /** @var ExpiredOrderCanceller */
    protected $cron;

    /** @var LoggerInterface */
    protected $logger;

    /**
     * ExpiredOrderCancellerCommand constructor.
     * @param State $state
     * @param ExpiredOrderCanceller $cron
     * @param LoggerInterface $logger
     * @param null $name
     */
    public function __construct(
        State $state,
        LoggerInterface $logger,
        ExpiredOrderCanceller $cron,
        $name = null
    ) {
        $this->cron = $cron;
        $this->logger = $logger;
        parent::__construct($name);
    }

    /**
     * Configure
     */
    protected function configure()
    {
        $this->setName('mercadopago:order:expired:cancel');
        $this->setDescription('Executa o job de cancelamento de pedidos expirados do modulo MercadoPago');
        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->cron->execute();
        } catch (\Exception $e) {
            $this->logger->error('Erro ao rodar job. Motivo: ' . $e->getMessage());
        }
    }
}