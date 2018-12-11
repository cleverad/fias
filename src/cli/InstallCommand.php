<?php

declare(strict_types=1);

namespace marvin255\fias\cli;

use marvin255\fias\service\config\ArrayConfig;
use marvin255\fias\service\log\SymfonyConsole;
use marvin255\fias\state\ArrayState;
use marvin255\fias\Factory;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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
                'mappers',
                InputArgument::REQUIRED,
                'Comma separated list of mappers classes to install'
            )
            ->addArgument(
                'workDir',
                InputArgument::REQUIRED,
                'Work directory for download and unpacking'
            )
            ->addArgument(
                'pdoDsn',
                InputArgument::REQUIRED,
                'DSN for database connection'
            )
            ->addArgument(
                'pdoUser',
                InputArgument::REQUIRED,
                'User for database connection'
            )
            ->addArgument(
                'pdoPassword',
                InputArgument::OPTIONAL,
                'Password for database connection'
            )
            ->addOption(
                'createStructure',
                'c',
                InputOption::VALUE_OPTIONAL,
                'Create tables for entities',
                false
            );
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = new ArrayConfig([
            'mappers' => $this->extractMappers($input),
            'workDir' => $input->getArgument('workDir'),
            'pdo.dsn' => $input->getArgument('pdoDsn'),
            'pdo.user' => $input->getArgument('pdoUser'),
            'pdo.password' => $input->getArgument('pdoPassword'),
            'createStructure' => $input->getOption('createStructure') !== false,
            'log' => new SymfonyConsole($output),
        ]);

        $pipe = (new Factory($config))->createInstallPipe();
        $state = new ArrayState;

        $pipe->run($state);
    }

    /**
     * Возвращает список мапперов, который задан в аргументах командной строки.
     *
     * @param InputInterface $input
     *
     * @return string[]
     */
    protected function extractMappers(InputInterface $input): array
    {
        $return = [];
        $mappers = $input->getArgument('mappers');

        if (is_string($mappers)) {
            $return = array_map('trim', explode(',', $mappers));
            $return = array_map(function (string $mapperName): string {
                return strpos($mapperName, '\\') === false
                    ? "\\marvin255\\fias\\mapper\\fias\\{$mapperName}"
                    : $mapperName;
            }, $return);
        }

        return $return;
    }
}
