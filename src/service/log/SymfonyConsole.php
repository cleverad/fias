<?php

declare(strict_types=1);

namespace marvin255\fias\service\log;

use Symfony\Component\Console\Output\OutputInterface;
use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;

/**
 * Объект лога, который выводит ответ в консоль.
 *
 * Служит оберткой для OutputInterface из Symfony console. Предназначен
 * для вывода данных в консоль при запуске скипта из console interface.
 */
class SymfonyConsole extends AbstractLogger
{
    /**
     * @var OutputInterface
     */
    protected $output;
    /**
     * @var string[]
     */
    protected $errorLevels = [
        LogLevel::EMERGENCY,
        LogLevel::ALERT,
        LogLevel::CRITICAL,
        LogLevel::ERROR,
    ];

    /**
     * @param OutputInterface $output
     */
    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * @inheritdoc
     */
    public function log($level, $message, array $context = [])
    {
        $type = in_array($level, $this->errorLevels) ? 'error' : 'info';
        $date = date('d.m.Y H:i:s');

        $this->output->writeln("<{$type}>{$date} -> {$message}</{$type}>");
    }
}
