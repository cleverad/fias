<?php

declare(strict_types=1);

namespace marvin255\fias\service\config;

/**
 * Интерфейс для объекта, который хранит конфигурацию.
 */
interface ConfigInterface
{
    /**
     * Возвращает значание опции по имени и приводит к строковому типу.
     *
     * @param string $optionName
     * @param string $defaultValue
     *
     * @return string
     */
    public function getString(string $optionName, string $defaultValue): string;

    /**
     * Возвращает значание опции по имени и приводит к целочисленному типу.
     *
     * @param string $optionName
     * @param int    $defaultValue
     *
     * @return int
     */
    public function getInt(string $optionName, int $defaultValue): int;

    /**
     * Возвращает значание опции по имени и приводит к булевскому типу.
     *
     * @param string $optionName
     * @param bool   $defaultValue
     *
     * @return bool
     */
    public function getBool(string $optionName, bool $defaultValue): bool;

    /**
     * Возвращает значание опции по имени  без приведения к типу.
     *
     * @param string $optionName
     *
     * @return mixed
     */
    public function getRaw(string $optionName);

    /**
     * Возвращает значание опции по имни в виде массива.
     *
     * @param string $optionName
     *
     * @return array
     */
    public function getArray(string $optionName): array;
}
