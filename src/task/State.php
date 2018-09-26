<?php

declare(strict_types=1);

namespace marvin255\fias\task;

/**
 * Интерфейс для объекта, который передает состояние между операциями.
 */
interface State
{
    /**
     * Возвращает параметр состояния по его имени.
     *
     * @param string $parameterName
     *
     * @return mixed
     */
    public function getParameter(string $parameterName);
}
