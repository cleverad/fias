<?php

declare(strict_types=1);

namespace marvin255\fias\tests\service\config;

use marvin255\fias\tests\BaseTestCase;
use marvin255\fias\service\config\YamlConfig;
use UnexpectedValueException;
use InvalidArgumentException;

/**
 * Тест для объекта, который содержит конфигурацию в yaml файле.
 */
class YamlConfigTest extends BaseTestCase
{
    /**
     * Проверяет, что конструктор выбросит исключение, если файла конфигурации
     * не существует.
     */
    public function testCostructUnexistedFileException()
    {
        $this->expectException(InvalidArgumentException::class);
        new YamlConfig('test.yaml');
    }

    /**
     * Проверяет, что конструктор выбросит исключение, если не сможет прочитать
     * yaml из файла.
     */
    public function testConstructWrongYamlException()
    {
        $this->expectException(InvalidArgumentException::class);
        new YamlConfig(__DIR__ . '/_fixture/testConstructWrongYamlException.yaml');
    }

    /**
     * Получение опции по имени в виде строки.
     */
    public function testGetString()
    {
        $additionalOptions = [
            'replace' => $this->faker()->word,
        ];

        $config = new YamlConfig(
            __DIR__ . '/_fixture/testGetString.yaml',
            $additionalOptions
        );

        $this->assertSame($additionalOptions['replace'], $config->getString('replace'));
        $this->assertSame('', $config->getString('unexisted_option'));
        $this->assertSame('string_option', $config->getString('string_option'));
        $this->assertSame('1', $config->getString('integer_option'));
        $this->assertSame('default', $config->getString('unexisted_option', 'default'));
    }

    /**
     * Проверяет, что объект производит автоподстановку абсолютных путей
     * вместо соответствующих замен.
     */
    public function testGetStringWithReplaces()
    {
        $config = new YamlConfig(
            __DIR__ . '/_fixture/testGetStringWithReplaces.yaml'
        );

        $this->assertSame(__DIR__ . '/_fixture/test/test.php', $config->getString('path'));
    }

    /**
     * Получение опции по имени в виде булева значения.
     */
    public function testGetBool()
    {
        $additionalOptions = [
            'replace' => false,
        ];

        $config = new YamlConfig(
            __DIR__ . '/_fixture/testGetBool.yaml',
            $additionalOptions
        );

        $this->assertFalse($config->getBool('integer'));
        $this->assertFalse($config->getBool('unexisted_option'));
        $this->assertFalse($config->getBool('replace'));
        $this->assertTrue($config->getBool('string'));
        $this->assertTrue($config->getBool('bool'));
        $this->assertTrue($config->getBool('unexisted_option', true));
    }

    /**
     * Получение опции по имени в виде целого числа.
     */
    public function testGetInt()
    {
        $additionalOptions = [
            'replace' => $this->faker()->randomDigitNotNull + 1,
        ];

        $config = new YamlConfig(
            __DIR__ . '/_fixture/testGetInt.yaml',
            $additionalOptions
        );

        $this->assertSame($additionalOptions['replace'], $config->getInt('replace'));
        $this->assertSame(1, $config->getInt('integer'));
        $this->assertSame(2, $config->getInt('string'));
        $this->assertSame(0, $config->getInt('unexisted_option'));
        $this->assertSame(123, $config->getInt('unexisted_option', 123));
    }

    /**
     * Проверка на то, что объект выбросит исключение, если не сможет преобразовать
     * значение опции в целое число при получении.
     */
    public function testGetIntNotNumberException()
    {
        $config = new YamlConfig(__DIR__ . '/_fixture/testGetIntNotNumberException.yaml');

        $this->expectException(UnexpectedValueException::class);
        $config->getInt('string');
    }

    /**
     * Проверяет получение объекта без приведения к типу.
     */
    public function testGetRaw()
    {
        $config = new YamlConfig(__DIR__ . '/_fixture/testGetRaw.yaml');

        $this->assertSame(['test' => ['test']], $config->getRaw('raw'));
    }

    /**
     * Проверяет получение массива.
     */
    public function testGetArray()
    {
        $config = new YamlConfig(__DIR__ . '/_fixture/testGetArray.yaml');

        $this->assertSame([1, 2, 3], $config->getArray('array'));
    }

    /**
     * Проверяет, что объект выьросит исключение при попытке получить опцию
     * как массив, если она массивом не являяется.
     */
    public function testGetArrayException()
    {
        $config = new YamlConfig(__DIR__ . '/_fixture/testGetArrayException.yaml');

        $this->expectException(UnexpectedValueException::class);
        $config->getArray('array');
    }
}
