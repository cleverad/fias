<?php

declare(strict_types=1);

namespace marvin255\fias\state;

/**
 * Интерфейс для объекта, который передает состояние между операциями.
 */
interface State
{
    /**
     * Задает параметр состояния по его имени.
     *
     * @param string $parameterName
     * @param mixed  $parameterValue
     *
     * @return $this
     */
    public function setParameter(string $parameterName, $parameterValue);

    /**
     * Возвращает параметр состояния по его имени.
     *
     * @param string $parameterName
     *
     * @return mixed
     */
    public function getParameter(string $parameterName);
}
