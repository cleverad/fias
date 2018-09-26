<?php

declare(strict_types=1);

namespace marvin255\fias\tests;

use marvin255\fias\task\Task;
use marvin255\fias\state\State;
use marvin255\fias\task\RuntimeException;
use marvin255\fias\Pipe;
use InvalidArgumentException;

/**
 * Тест для базового объекта приложения, который выполняет основные задачи,
 * для импорта данных из ФИАС.
 */
class PipeTest extends BaseTestCase
{
    /**
     * Проверяет, что задачи добавляются в очередь и запускаются.
     */
    public function testRun()
    {
        $state = $this->getMockBuilder(State::class)->getMock();

        $task1 = $this->getMockBuilder(Task::class)->getMock();
        $task1->expects($this->once())->method('run')->with($this->equalTo($state));

        $task2 = $this->getMockBuilder(Task::class)->getMock();
        $task2->expects($this->once())->method('run')->with($this->equalTo($state));

        $pipe = new Pipe;
        $pipe->pipe($task1);
        $pipe->pipe($task2);
        $pipe->run($state);
    }

    /**
     * Проверяет, что объект приложения перехватит любое исключение и выбросит
     * унифицированный тип.
     */
    public function testRunException()
    {
        $state = $this->getMockBuilder(State::class)->getMock();

        $task1 = $this->getMockBuilder(Task::class)->getMock();

        $task2 = $this->getMockBuilder(Task::class)->getMock();
        $task2->method('run')->will($this->throwException(new InvalidArgumentException));

        $pipe = new Pipe;
        $pipe->pipe($task1);
        $pipe->pipe($task2);

        $this->expectException(RuntimeException::class);
        $pipe->run($state);
    }
}
