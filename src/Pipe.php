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
     * @var TaskInterface[]
     */
    private $tasks = [];
    /**
     * @var TaskInterface|null
     */
    private $cleanup;

    /**
     * Регистрирует операцию в приложении.
     *
     * @param TaskInterface $task
     *
     * @return $this
     */
    public function pipe(TaskInterface $task): self
    {
        $this->tasks[] = $task;

        return $this;
    }

    /**
     * Задает задачу, которая будет запущена по штатному или нештатному
     * завершению цепочки задач.
     *
     * @param TaskInterface $task
     *
     * @return $this
     */
    public function setCleanup(TaskInterface $cleanup): self
    {
        $this->cleanup = $cleanup;

        return $this;
    }

    /**
     * Запускает все операции на выполнение.
     *
     * @param StateInterface $state
     *
     * @return $this
     *
     * @throws RuntimeException
     */
    public function run(StateInterface $state): self
    {
        foreach ($this->tasks as $task) {
            try {
                $task->run($state);
            } catch (Exception $e) {
                $this->cleanup($state);
                throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
            }
            if ($state->isCompleted()) {
                break;
            }
        }

        $this->cleanup($state);

        return $this;
    }

    /**
     * Обработка завершения задачи.
     *
     * @param StateInterface $state
     *
     * @return void
     */
    protected function cleanup(StateInterface $state)
    {
        if ($this->cleanup) {
            $this->cleanup->run($state);
        }
    }
}
