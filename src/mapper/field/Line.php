<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\field;

use marvin255\fias\mapper\FieldInterface;

/**
 * Строковый тип поля.
 */
class Line implements FieldInterface
{
    /**
     * @inheritdoc
     */
    public function convert(string $input): string
    {
        return $input;
    }
}
