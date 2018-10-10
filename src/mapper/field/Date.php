<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\field;

use marvin255\fias\mapper\FieldInterface;
use DateTime;

/**
 * Поле с датой.
 */
class Date implements FieldInterface
{
    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function convert(string $input): DateTime
    {
        return new DateTime($input);
    }
}
