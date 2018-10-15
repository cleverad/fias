<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\field;

/**
 * Строковый тип поля.
 */
class Line extends AbstractField
{
    /**
     * Максимальная длина строки.
     *
     * @var int
     */
    protected $length = 255;

    /**
     * @param int $length
     */
    public function __construct(int $length = 255)
    {
        $this->length = $length;
    }

    /**
     * Возвращает максимальную длину строки.
     *
     * @return int
     */
    public function getLength(): int
    {
        return $this->length;
    }
}
