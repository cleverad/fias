<?php

declare(strict_types=1);

namespace marvin255\fias\tests\mapper\field;

use marvin255\fias\tests\BaseTestCase;
use marvin255\fias\mapper\field\Line;
use InvalidArgumentException;

/**
 * Тест для строкового поля сущности.
 */
class LineTest extends BaseTestCase
{
    /**
     * Проверяет, что поле корректно возвращает длину числа.
     */
    public function testGetLength()
    {
        $length = $this->faker()->randomNumber + 1;

        $field = new Line($length);

        $this->assertSame($length, $field->getLength());
    }

    /**
     * Проверяет, что поле верно конвертирует строку в данные.
     */
    public function testConvertToData()
    {
        $value = $this->faker()->text;

        $field = new Line;

        $this->assertSame($value, $field->convertToData($value));
    }

    /**
     * Проверяет, что объект выбросит исключение при попытке конвертации
     * строки, которая превышает заданно количество символов.
     */
    public function testConvertToDataLengthException()
    {
        $value = $this->faker()->lexify('test ???????');
        $field = new Line(11);

        $this->expectException(InvalidArgumentException::class);
        $field->convertToData($value);
    }

    /**
     * Проверяет, что поле верно конвертирует данные в строку.
     */
    public function testConvertToString()
    {
        $value = $this->faker()->text;

        $field = new Line;

        $this->assertSame($value, $field->convertToString($value));
    }

    /**
     * Проверяет, что объект выбросит исключение при попытке конвертации
     * строки, которая превышает заданно количество символов.
     */
    public function testConvertToStringLengthException()
    {
        $value = $this->faker()->lexify('test ???????');
        $field = new Line(11);

        $this->expectException(InvalidArgumentException::class);
        $field->convertToString($value);
    }
}
