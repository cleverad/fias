<?php

declare(strict_types=1);

namespace marvin255\fias\task;

use marvin255\fias\state\State;

/**
 * Интерфейс для объекта, который выполняет отдельную операцию для импорта или
 * обновления.
 */
interface Task
{
    /**
     * Запускает операцию на исполнение.
     *
     * @param \marvin255\fias\state\State $state Объект, в котором хранится состояние импорта для передачи между операциями
     */
    public function run(State $state);
}
