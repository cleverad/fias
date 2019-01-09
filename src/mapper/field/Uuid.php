<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\field;

/**
 * Поле с uuid.
 */
class Uuid extends Line
{
    public function __construct()
    {
        $this->length = 36;
    }
}
