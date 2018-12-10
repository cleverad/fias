<?php

declare(strict_types=1);

namespace marvin255\fias\tests\service\log;

use marvin255\fias\tests\BaseTestCase;
use marvin255\fias\service\log\SymfonyConsole;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Тест для объекта лога, который выводит сообщения в консоль с помощью
 * symfony console.
 */
class SymfonyConsoleTest extends BaseTestCase
{
    /**
     * Проверяет, что запись лога уходит в объект OutputInterface и
     * отмечается как информационное сообщение.
     */
    public function testLogInfo()
    {
        $message = $this->faker()->text;
        $output = $this->getMockBuilder(OutputInterface::class)->getMock();
        $output->expects($this->once())
            ->method('writeln')
            ->with($this->equalTo("<info>{$message}</info>"));

        $log = new SymfonyConsole($output);

        $log->info($message);
    }

    /**
     * Проверяет, что запись лога уходит в объект OutputInterface и
     * отмечается как сообщение об ошибке.
     */
    public function testLogError()
    {
        $message = $this->faker()->text;
        $output = $this->getMockBuilder(OutputInterface::class)->getMock();
        $output->expects($this->once())
            ->method('writeln')
            ->with($this->equalTo("<error>{$message}</error>"));

        $log = new SymfonyConsole($output);

        $log->error($message);
    }
}
