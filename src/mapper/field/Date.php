<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\field;

use DateTime;
use DateTimeInterface;
use InvalidArgumentException;
use Exception;

/**
 * Поле с датой.
 */
class Date extends AbstractField
{
    /**
     * {@inheritdoc}
     *
     * @throws Exception
     */
    public function convertToData(string $input): DateTime
    {
        return new DateTime($input);
    }

    /**
     * {@inheritdoc}
     *
     * @throws InvalidArgumentException
     */
    public function convertToString($input): string
    {
        if (!($input instanceof DateTimeInterface)) {
            throw new InvalidArgumentException(
                'Field value must be a DateTimeInterface to convert to string'
            );
        }

        return $input->format('Y-m-d H:i:s');
    }
}
