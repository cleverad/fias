<?php

declare(strict_types=1);

namespace marvin255\fias\cli;

use marvin255\fias\service\config\YamlConfig;
use marvin255\fias\service\log\SymfonyConsole;
use marvin255\fias\state\ArrayState;
use marvin255\fias\factory\InternalServicesFactory;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;

/**
 * Консольная команда для установки ФИАС с ноля.
 */
class InstallCommand extends Command
{
    /**
     * {@inheritdoc}
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('fias:install')
            ->setDescription('Install FIAS')
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

        $pipe = (new InternalServicesFactory($configObject))->createInstallPipe();
        $state = new ArrayState;

        $pipe->run($state);
    }
}
