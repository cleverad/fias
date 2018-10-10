<?php

declare(strict_types=1);

namespace marvin255\fias\tests\mapper\field;

use marvin255\fias\tests\BaseTestCase;
use marvin255\fias\mapper\field\Date;

/**
 * Тест поля сущности с датой.
 */
class DateTest extends BaseTestCase
{
    /**
     * Проверяет, что поле верно конвертирует результат.
     */
    public function testConvert()
    {
        $value = '10.10.2018';

        $field = new Date;

        $this->assertSame($value, $field->convert($value)->format('d.m.Y'));
    }
}
