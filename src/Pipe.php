<?php

declare(strict_types=1);

namespace marvin255\fias;

use marvin255\fias\task\TaskInterface;
use marvin255\fias\task\RuntimeException;
use marvin255\fias\state\StateInterface;
use Exception;

/**
 * Основной объект приложения, который запускает зарегистрированные операции на
 * выполнение.
 */
class Pipe
{
    /**
     * @var \marvin255\fias\task\TaskInterface[]
     */
    private $tasks = [];

    /**
     * Регистрирует операцию в приложении.
     *
     * @param \marvin255\fias\task\TaskInterface $task
     *
     * @return $this
     */
    public function pipe(TaskInterface $task): self
    {
        $this->tasks[] = $task;

        return $this;
    }

    /**
     * Запускает все операции на выполнение.
     *
     * @param \marvin255\fias\state\StateInterface $state
     *
     * @return $this
     *
     * @throws \marvin255\fias\task\RuntimeException
     */
    public function run(StateInterface $state): self
    {
        foreach ($this->tasks as $task) {
            try {
                $task->run($state);
            } catch (Exception $e) {
                throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
            }
            if ($state->isCompleted()) {
                break;
            }
        }

        return $this;
    }
}
