<?php

declare(strict_types=1);

namespace marvin255\fias\tests\state;

use marvin255\fias\tests\BaseTestCase;
use marvin255\fias\state\ArrayState;

/**
 * Тест для объекта, который передает состояние между операциями через внутренний
 * массив.
 */
class ArrayStateTest extends BaseTestCase
{
    /**
     * Проверяем запись и получение параметра.
     */
    public function testSetAndGetParameter()
    {
        $parameterName = $this->faker()->unique()->word;
        $parameterValue = $this->faker()->unique()->word;

        $state = new ArrayState;
        $state->setParameter($parameterName, $parameterValue);

        $this->assertSame($parameterValue, $state->getParameter($parameterName));
    }

    /**
     * Проверяем флаг, который мягко прерывает исполнение операций.
     */
    public function testComplete()
    {
        $state = new ArrayState;

        $stateCompleted = new ArrayState;
        $stateCompleted->complete();

        $this->assertFalse($state->isCompleted());
        $this->assertTrue($stateCompleted->isCompleted());
    }
}
