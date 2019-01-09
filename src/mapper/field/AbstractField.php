<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\field;

/**
 * Абстрактный класс для поля маппера.
 */
abstract class AbstractField implements FieldInterface
{
    /**
     * @inheritdoc
     */
    public function convertToString($input): string
    {
        return (string) $input;
    }
}
