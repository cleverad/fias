<?php

declare(strict_types=1);

namespace marvin255\fias\mapper;

/**
 * Интерфейс для поля сущности.
 */
interface FieldInterface
{
    /**
     * Конвертирует входящий параметр к типу, соответсвующему данному полю.
     *
     * @param string $input
     *
     * @return mixed
     */
    public function convert(string $input);
}
