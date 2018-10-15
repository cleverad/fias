<?php

declare(strict_types=1);

namespace marvin255\fias\tests\mapper\field;

use marvin255\fias\tests\BaseTestCase;
use marvin255\fias\mapper\field\IntNumber;

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
        $length = $this->faker()->unique()->randomNumber + 1;

        $field = new IntNumber($length);

        $this->assertSame($length, $field->getLength());
    }

    /**
     * Проверяет, что поле верно конвертирует строку в данные.
     */
    public function testConvertToData()
    {
        $value = (string) $this->faker()->unique()->randomNumber;
        $valueString = $this->faker()->unique()->randomNumber . 'word';

        $field = new IntNumber;

        $this->assertSame((int) $value, $field->convertToData($value));
        $this->assertSame((int) $valueString, $field->convertToData($valueString));
    }

    /**
     * Проверяет, что поле верно конвертирует данные в строку.
     */
    public function testConvertToString()
    {
        $value = (string) $this->faker()->unique()->randomNumber;
        $valueString = $this->faker()->unique()->randomNumber . 'word';

        $field = new IntNumber;

        $this->assertSame((string) $value, $field->convertToString($value));
        $this->assertSame((string) $valueString, $field->convertToString($valueString));
    }
}
