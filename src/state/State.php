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
    public function setParameter(string $parameterName, $parameterValue): State;

    /**
     * Возвращает параметр состояния по его имени.
     *
     * @param string $parameterName
     *
     * @return mixed
     */
    public function getParameter(string $parameterName);

    /**
     * Команда, которая отмечает, что нужно мягко прервать цепочку операций.
     *
     * @return $this
     */
    public function complete(): State;

    /**
     * Метод, который указывает, что цепочка должна быть прервана после текушей
     * операции.
     *
     * @return bool
     */
    public function isCompleted(): bool;
}
