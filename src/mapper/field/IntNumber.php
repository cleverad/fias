<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\field;

use InvalidArgumentException;

/**
 * Целочисленный тип поля.
 */
class IntNumber extends AbstractField
{
    /**
     * Максимальная длина числа.
     *
     * @var int
     */
    protected $length = 10;

    /**
     * @param int $length
     */
    public function __construct(int $length = 10)
    {
        $this->length = $length;
    }

    /**
     * Возвращает максимальную длину числа.
     *
     * @return int
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * {@inheritdoc}
     *
     * @throws InvalidArgumentException
     */
    public function convertToString($input): string
    {
        return (string) $this->checkNumber((string) $input);
    }

    /**
     * {@inheritdoc}
     *
     * @throws InvalidArgumentException
     */
    public function convertToData(string $input)
    {
        return (int) $this->checkNumber($input);
    }

    /**
     * Проверяет число согласно параметрам.
     *
     * @throws InvalidArgumentException
     */
    protected function checkNumber(string $input): string
    {
        $input = trim($input);

        if (!preg_match('/^\d+$/', $input)) {
            throw new InvalidArgumentException(
                "String must contains only digits, got: {$input}"
            );
        }

        if ($this->length && mb_strlen($input) > $this->length) {
            throw new InvalidArgumentException(
                "String length must be less or equal than {$this->length}, got: {$input}"
            );
        }

        return $input;
    }
}
