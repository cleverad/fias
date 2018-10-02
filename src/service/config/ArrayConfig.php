<?php

declare(strict_types=1);

namespace marvin255\fias\service\config;

use UnexpectedValueException;

/**
 * Объект, который хранит конфигурацию запуска во внутрннем массиве и настраивается
 * при создании.
 */
class ArrayConfig implements ConfigInterface
{
    /**
     * @var array
     */
    protected $options = [];

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * @inherit
     */
    public function getString(string $optionName, string $defaultValue = ''): string
    {
        if (isset($this->options[$optionName])) {
            $defaultValue = (string) $this->options[$optionName];
        }

        return $defaultValue;
    }

    /**
     * @inherit
     */
    public function getInt(string $optionName, int $defaultValue = 0): int
    {
        if (isset($this->options[$optionName])) {
            if (!is_int($this->options[$optionName]) && !is_numeric($this->options[$optionName])) {
                throw new UnexpectedValueException(
                    "Can't convert {$optionName} value to integer"
                );
            }
            $defaultValue = (int) $this->options[$optionName];
        }

        return $defaultValue;
    }

    /**
     * @inherit
     */
    public function getBool(string $optionName, bool $defaultValue = false): bool
    {
        if (isset($this->options[$optionName])) {
            $defaultValue = (bool) $this->options[$optionName];
        }

        return $defaultValue;
    }
}
