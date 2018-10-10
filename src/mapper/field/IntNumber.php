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
     * @inheritdoc
     */
    public function convert(string $input): int
    {
        return (int) $input;
    }
}
