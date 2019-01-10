<?php

declare(strict_types=1);

namespace marvin255\fias\tests\mapper\field;

use marvin255\fias\tests\BaseTestCase;
use marvin255\fias\mapper\field\Uuid;
use InvalidArgumentException;

/**
 * Тест для строкового поля сущности.
 */
class UuidTest extends BaseTestCase
{
    /**
     * Проверяет, что поле верно конвертирует строку в данные.
     */
    public function testConvertToData()
    {
        $value = $this->faker()->uuid;

        $field = new Uuid;

        $this->assertSame($value, $field->convertToData($value));
    }

    /**
     * Проверяет, что объект выбросит исключение при попытке конвертации
     * строки, которая не является валидным uuid.
     */
    public function testConvertToDataInvaliUuidException()
    {
        $value = $this->faker()->word;
        $field = new Uuid;

        $this->expectException(InvalidArgumentException::class);
        $field->convertToData($value);
    }

    /**
     * Проверяет, что поле верно конвертирует данные в строку.
     */
    public function testConvertToString()
    {
        $value = $this->faker()->uuid;

        $field = new Uuid;

        $this->assertSame($value, $field->convertToString($value));
    }

    /**
     * Проверяет, что объект выбросит исключение при попытке конвертации
     * строки, которая не является валидным uuid.
     */
    public function testConvertToStringInvaliUuidException()
    {
        $value = $this->faker()->word;
        $field = new Uuid;

        $this->expectException(InvalidArgumentException::class);
        $field->convertToString($value);
    }
}
