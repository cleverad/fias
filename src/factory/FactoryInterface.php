<?php

declare(strict_types=1);

namespace marvin255\fias\factory;

use marvin255\fias\Pipe;

/**
 * Фабричный объект, который создает пайпы для соответствующих типов задач.
 */
interface FactoryInterface
{
    /**
     * Создает пайп для полной загрузки базы ФИАС.
     *
     * @return Pipe
     */
    public function createInstallPipe(): Pipe;

    /**
     * Создает пайп для обновления базы ФИАС относительно указанной версии.
     *
     * @return Pipe
     */
    public function createUpdatePipe(): Pipe;
}
