<?php

declare(strict_types=1);

namespace marvin255\fias\task;

/**
 * Абстрактный класс для задачи.
 */
abstract class AbstractTask implements TaskInterface
{
    /**
     * @var \Psr\Log\LoggerInterface|null
     */
    protected $logger;

    /**
     * Записывает в лог информацию с уровнем INFO.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    protected function info(string $message, array $context = [])
    {
        if ($this->logger) {
            $this->logger->info($message, $context);
        }
    }
}
