<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\field;

use DateTime;

/**
 * Поле с датой.
 */
class Date extends AbstractField
{
    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function convertToData(string $input): DateTime
    {
        return new DateTime($input);
    }

    /**
     * @inheritdoc
     */
    public function convertToString($input): string
    {
        $return = '';

        if ($input instanceof DateTime) {
            $return = $input->format('Y-m-d H:i:s');
        }

        return $return;
    }
}
