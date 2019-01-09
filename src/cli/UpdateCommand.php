<?php

declare(strict_types=1);

namespace marvin255\fias\cli;

use marvin255\fias\service\config\YamlConfig;
use marvin255\fias\service\log\SymfonyConsole;
use marvin255\fias\state\ArrayState;
use marvin255\fias\factory\InternalServicesFactory;
use marvin255\fias\service\fias\InformerResult;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;

/**
 * Консольная команда для обновления ФИАС относительно указанной версии.
 */
class UpdateCommand extends Command
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
            );
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $confFile = $input->getArgument('config');
        if (!$confFile || !is_string($confFile)) {
            $confFile = getcwd() . '/.conf.yaml';
        }

        $configObject = new YamlConfig($confFile, [
            'log' => new SymfonyConsole($output),
        ]);

        $pipe = (new InternalServicesFactory($configObject))->createUpdatePipe();

        $informerResult = new InformerResult;
        $informerResult->setVersion((int) $input->getArgument('version'));

        $state = new ArrayState;
        $state->setParameter('informerResult', $informerResult);

        $pipe->run($state);
    }
}
