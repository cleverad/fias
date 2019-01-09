<?php

declare(strict_types=1);

namespace marvin255\fias\tests\mapper\field;

use marvin255\fias\tests\BaseTestCase;
use marvin255\fias\mapper\field\Date;
use DateTime;
use InvalidArgumentException;

/**
 * Тест поля сущности с датой.
 */
class DateTest extends BaseTestCase
{
    /**
     * Проверяет, что поле верно конвертирует строку в данные.
     */
    public function testConvertToData()
    {
        $value = $this->faker()->date('d.m.Y');

        $field = new Date;

        $this->assertSame($value, $field->convertToData($value)->format('d.m.Y'));
    }

    /**
     * Проверяет, что поле верно конвертирует данные в строку.
     */
    public function testConvertToString()
    {
        $value = $this->faker()->date('Y-m-d H:i:s');

        $field = new Date;

        $this->assertSame($value, $field->convertToString(new DateTime($value)));
    }

    /**
     * Проверяет, что объект выбросит исключение при попытке конвертации чего-либо
     * отличного от DateTimeInterface в строку.
     */
    public function testConvertToStringNotDateTimeInterfaceException()
    {
        $field = new Date;

        $this->expectException(InvalidArgumentException::class);
        $field->convertToString($this->faker()->date('d.m.Y'));
    }
}
