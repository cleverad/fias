<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\field;

use marvin255\fias\mapper\FieldInterface;

/**
 * Целочисленный тип поля.
 */
class IntNumber implements FieldInterface
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
     * @inheritdoc
     */
    public function convert(string $input): int
    {
        return (int) $input;
    }
}
