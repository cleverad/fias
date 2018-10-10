<?php

declare(strict_types=1);

namespace marvin255\fias\tests\mapper\field;

use marvin255\fias\tests\BaseTestCase;
use marvin255\fias\mapper\field\Line;

/**
 * Тест для строкового поля сущности.
 */
class LineTest extends BaseTestCase
{
    /**
     * Проверяет, что поле верно конвертирует результат.
     */
    public function testConvert()
    {
        $value = $this->faker()->unique()->text;

        $field = new Line;

        $this->assertSame($value, $field->convert($value));
    }
}
