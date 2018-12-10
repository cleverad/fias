<?php

declare(strict_types=1);

namespace marvin255\fias\task;

use marvin255\fias\state\StateInterface;

/**
 * Интерфейс для объекта, который выполняет отдельную операцию для импорта или
 * обновления.
 */
interface TaskInterface
{
    /**
     * Запускает операцию на исполнение.
     *
     * @param StateInterface $state Объект, в котором хранится состояние импорта для передачи между операциями
     *
     * @return void
     *
     * @throws \Exception
     */
    public function run(StateInterface $state);
}
