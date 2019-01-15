<?php

declare(strict_types=1);

namespace marvin255\fias\cli;

use marvin255\fias\state\ArrayState;
use marvin255\fias\service\fias\InformerResult;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 * Консольная команда для обновления ФИАС относительно указанной версии.
 */
class UpdateCommand extends AbstractCommand
{
    /**
     * {@inheritdoc}
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('fias:update')
            ->setDescription('Update FIAS')
            ->addArgument(
                'version',
                InputArgument::REQUIRED,
                'Current version of FIAS'
            )
            ->addArgument(
                'config',
                InputArgument::OPTIONAL,
                'Path to config file'
            )
            ->addOption(
                'factory',
                'f',
                InputOption::VALUE_OPTIONAL,
                'Full specified class name for factory'
            )
        ;
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = $this->createConfigObject($input, $output);
        $factory = $this->createFactoryObject($config, $input, $output);
        $pipe = $factory->createUpdatePipe();

        $informerResult = new InformerResult;
        $informerResult->setVersion((int) $input->getArgument('version'));

        $state = new ArrayState;
        $state->setParameter('informerResult', $informerResult);

        $pipe->run($state);
    }
}
