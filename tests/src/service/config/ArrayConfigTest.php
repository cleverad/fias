<?php

declare(strict_types=1);

namespace marvin255\fias\tests\service\config;

use marvin255\fias\tests\BaseTestCase;
use marvin255\fias\service\config\ArrayConfig;
use UnexpectedValueException;

/**
 * Тест для объекта, который содержит конфигурацию загрузки во внутреннем
 * массиве.
 */
class ArrayConfigTest extends BaseTestCase
{
    /**
     * Получение опции по имени в виде строки.
     */
    public function testGetString()
    {
        $options = [
            'integer' => $this->faker()->unique()->randomDigitNotNull,
            'string' => $this->faker()->unique()->word,
        ];

        $config = new ArrayConfig($options);

        $this->assertSame((string) $options['integer'], $config->getString('integer'));
        $this->assertSame($options['string'], $config->getString('string'));
        $this->assertSame('', $config->getString('unexisted_option'));
        $this->assertSame('default', $config->getString('unexisted_option', 'default'));
    }

    /**
     * Получение опции по имени в виде булева значения.
     */
    public function testGetBool()
    {
        $options = [
            'integer' => 0,
            'string' => '1',
            'bool' => true,
            'null' => null,
        ];

        $config = new ArrayConfig($options);

        $this->assertFalse($config->getBool('integer'));
        $this->assertFalse($config->getBool('null'));
        $this->assertFalse($config->getBool('unexisted_option'));
        $this->assertTrue($config->getBool('string'));
        $this->assertTrue($config->getBool('bool'));
        $this->assertTrue($config->getBool('unexisted_option', true));
    }

    /**
     * Получение опции по имени в виде целого числа.
     */
    public function testGetInt()
    {
        $options = [
            'integer' => $this->faker()->unique()->randomDigitNotNull,
            'string' => (string) $this->faker()->unique()->randomDigitNotNull,
        ];

        $config = new ArrayConfig($options);

        $this->assertSame($options['integer'], $config->getInt('integer'));
        $this->assertSame((int) $options['string'], $config->getInt('string'));
        $this->assertSame(0, $config->getInt('unexisted_option'));
        $this->assertSame(123, $config->getInt('unexisted_option', 123));
    }

    /**
     * Проверка на то, что объект выбросит исключение, если не сможет преобразовать
     * значение опции в целое число при получении.
     */
    public function testGetIntNotNumberException()
    {
        $options = [
            'string' => $this->faker()->unique()->word,
        ];

        $config = new ArrayConfig($options);

        $this->expectException(UnexpectedValueException::class);
        $config->getInt('string');
    }

    /**
     * Проверяет получение объекта без приведения к типу.
     */
    public function testGetRaw()
    {
        $raw = new \stdClass;

        $config = new ArrayConfig(['raw' => $raw]);

        $this->assertSame($raw, $config->getRaw('raw'));
    }

    /**
     * Проверяет получение массива.
     */
    public function testGetArray()
    {
        $array = [
            $this->faker()->word,
            $this->faker()->word,
            $this->faker()->word,
        ];

        $config = new ArrayConfig(['array' => $array]);

        $this->assertSame($array, $config->getArray('array'));
    }

    /**
     * Проверяет, что объект выьросит исключение при попытке получить опцию
     * как массив, если она массивом не являяется.
     */
    public function testGetArrayException()
    {
        $array = $this->faker()->word;

        $config = new ArrayConfig(['array' => $array]);

        $this->expectException(UnexpectedValueException::class);
        $config->getArray('array');
    }
}
