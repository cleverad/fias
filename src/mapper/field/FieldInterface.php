<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\field;

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
    public function convertToData(string $input);

    /**
     * Конвертирует входящий параметр к строке, для записи в БД.
     *
     * @param mixed $input
     *
     * @return string
     */
    public function convertToString($input): string;
}
