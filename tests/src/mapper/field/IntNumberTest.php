<?php

declare(strict_types=1);

namespace marvin255\fias\tests\mapper\field;

use marvin255\fias\tests\BaseTestCase;
use marvin255\fias\mapper\field\IntNumber;
use InvalidArgumentException;

/**
 * Тест для целочисленного поля сущности.
 */
class IntNumberTest extends BaseTestCase
{
    /**
     * Проверяет, что поле корректно возвращает длину числа.
     */
    public function testGetLength()
    {
        $length = $this->faker()->randomNumber + 1;

        $field = new IntNumber($length);

        $this->assertSame($length, $field->getLength());
    }

    /**
     * Проверяет, что поле верно конвертирует строку в данные.
     */
    public function testConvertToData()
    {
        $value = (string) $this->faker()->randomNumber;

        $field = new IntNumber;

        $this->assertSame((int) $value, $field->convertToData($value));
    }

    /**
     * Проверяет, что объект выбросит исключение при попытке конвертации
     * строки, которая превышает заданне количество символов.
     */
    public function testConvertToDataLengthException()
    {
        $value = (string) $this->faker()->numberBetween(100, 999);
        $field = new IntNumber(2);

        $this->expectException(InvalidArgumentException::class);
        $field->convertToData($value);
    }

    /**
     * Проверяет, что объект выбросит исключение при попытке конвертации
     * строки, которая состоит не только из цифр.
     */
    public function testConvertToDataNotNumericException()
    {
        $value = $this->faker()->lexify('???????');
        $field = new IntNumber(10);

        $this->expectException(InvalidArgumentException::class);
        $field->convertToData($value);
    }

    /**
     * Проверяет, что поле верно конвертирует данные в строку.
     */
    public function testConvertToString()
    {
        $value = (string) $this->faker()->randomNumber;

        $field = new IntNumber;

        $this->assertSame((string) $value, $field->convertToString($value));
    }

    /**
     * Проверяет, что объект выбросит исключение при попытке конвертации
     * числа, которое превышает заданное количество символов.
     */
    public function testConvertToStringLengthException()
    {
        $value = $this->faker()->numberBetween(100, 999);
        $field = new IntNumber(2);

        $this->expectException(InvalidArgumentException::class);
        $field->convertToString($value);
    }
}
