<?php

declare(strict_types=1);

namespace Treinamento\CommandInterfaceExample\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class CommandExample extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName("example:execute");
        $this->setDescription("Magento Essentials Example")
            ->addArgument(
                'message',
                InputArgument::OPTIONAL,
                'Message?'
            );

        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Hello World: ' . $input->getArgument('message'));
    }
}
