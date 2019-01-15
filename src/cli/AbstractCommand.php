<?php

declare(strict_types=1);

namespace marvin255\fias\cli;

use marvin255\fias\service\config\YamlConfig;
use marvin255\fias\service\log\SymfonyConsole;
use marvin255\fias\factory\FactoryInterface;
use marvin255\fias\factory\InternalServicesFactory;
use marvin255\fias\service\config\ConfigInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use ReflectionClass;
use RuntimeException;
use Exception;

/**
 * Абстрактная консольная команда для ФИАС.
 */
abstract class AbstractCommand extends Command
{
    /**
     * Создает и возвращает объект настроек.
     *
     * @throws Exception
     */
    public function createConfigObject(InputInterface $input, OutputInterface $output): ConfigInterface
    {
        $confFile = $input->getArgument('config');
        if (!$confFile || !is_string($confFile)) {
            $confFile = getcwd() . '/.conf.yaml';
        }

        return new YamlConfig($confFile, [
            'log' => new SymfonyConsole($output),
        ]);
    }

    /**
     * Создает и возвращает объект фабрики.
     *
     * @throws Exception
     */
    public function createFactoryObject(ConfigInterface $config, InputInterface $input, OutputInterface $output): FactoryInterface
    {
        $class = $input->getOption('title') ?: InternalServicesFactory::class;
        $factoryInstance = (new ReflectionClass($class))->newInstance($config);

        if (!($factoryInstance instanceof FactoryInterface)) {
            throw new RuntimeException(
                'Factory param must be instance of ' . FactoryInterface::class . ", {$class} given"
            );
        }

        return $factoryInstance;
    }
}
