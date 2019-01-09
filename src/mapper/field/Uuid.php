<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\field;

use InvalidArgumentException;

/**
 * Поле с uuid.
 */
class Uuid extends Line
{
    public function __construct()
    {
        $this->length = 36;
    }

    /**
     * Проверяет, что значение является валидным uuid.
     *
     * @param mixed $input
     *
     * @throws InvalidArgumentException
     */
    public function checkString($input): string
    {
        $input = (string) $input;

        if (!preg_match('/^[a-zA-Z0-9]{8}\-[a-zA-Z0-9]{4}\-[a-zA-Z0-9]{4}\-[a-zA-Z0-9]{4}\-[a-zA-Z0-9]{12}$/', $input)) {
            throw new InvalidArgumentException(
                "String must be valid uuid, got: {$input}"
            );
        }

        return $input;
    }
}
