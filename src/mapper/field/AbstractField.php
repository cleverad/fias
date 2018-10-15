<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\field;

use marvin255\fias\mapper\FieldInterface;

/**
 * Абстрактный класс для поля маппера.
 */
abstract class AbstractField implements FieldInterface
{
    /**
     * @inheritdoc
     */
    public function convertToData(string $input)
    {
        return $input;
    }

    /**
     * @inheritdoc
     */
    public function convertToString($input): string
    {
        return (string) $input;
    }
}
