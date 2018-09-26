<?php

declare(strict_types=1);

namespace marvin255\fias\task;

/**
 * Интерфейс для объекта, который выполняет отдельную операцию для импорта или
 * обновления.
 */
interface Task
{
    /**
     * Запускает операцию на исполнение.
     *
     * @param \marvin255\fias\task\State $state Объект, в котором хранится состояние импорта для передачи между операциями
     */
    public function run(State $state);
}
