<?php

declare(strict_types=1);

namespace marvin255\fias\tests\service\xml;

use marvin255\fias\tests\BaseTestCase;
use marvin255\fias\service\xml\Reader;
use RuntimeException;

/**
 * Тест для объекта, который читает данные из xml.
 */
class ReaderTest extends BaseTestCase
{
    /**
     * Проверяет, что объект не открывает файл, который не соответствует мапперу,
     * и открывает файл, который мапперу соответствует.
     */
    public function testOpen()
    {
        $mapper = new MockMapper('/root/firstLevel/secondLevel/realItem', [
            'thirdParam',
            'firstParam',
            'secondParam',
        ]);

        $reader = new Reader;
        $reader->setMapper($mapper);

        $this->assertTrue($reader->openFile(__DIR__ . '/_fixture/test_open_true.xml'));
        $this->assertFalse($reader->openFile(__DIR__ . '/_fixture/test_open_false.xml'));
    }

    /**
     * Проверяет, что при попытке открыть файл в объект без указания маппера
     * будет выброшено исключение.
     */
    public function testOpenNoMapperException()
    {
        $reader = new Reader;

        $this->expectException(RuntimeException::class, $sourcePath);
        $reader->openFile(__DIR__ . '/_fixture/test_open_true.xml');
    }

    /**
     * Проверяет, что при попытке итерировать по объекту без открытого файла
     * будет выброшено исключение.
     */
    public function testOpenNotOpenException()
    {
        $reader = new Reader;

        $this->expectException(RuntimeException::class, $sourcePath);
        foreach ($reader as $item) {
        }
    }

    /**
     * Проверяет, что при попытке открыть файл в объект без указания маппера
     * будет выброшено исключение.
     */
    public function testOpenWrongFileFormatException()
    {
        $mapper = new MockMapper('/root/firstLevel/secondLevel/realItem', [
            'thirdParam',
            'firstParam',
            'secondParam',
        ]);

        $reader = new Reader;
        $reader->setMapper($mapper);

        $this->expectException(RuntimeException::class, $sourcePath);
        $reader->openFile(__DIR__ . '/_fixture/test_open_wrong_file_format.xml');
    }

    /**
     * Проверяет что объект читает данные из файла и реализует итератор.
     */
    public function testIterator()
    {
        $mapper = new MockMapper('/root/firstLevel/secondLevel/realItem', [
            'thirdParam',
            'firstParam',
            'secondParam',
        ]);

        $reader = new Reader;
        $reader->setMapper($mapper)->openFile(__DIR__ . '/_fixture/test_iterator.xml');

        $etalonData = include __DIR__ . '/_fixture/test_iterator_result.php';
        $readedData = [];
        foreach ($reader as $item) {
            $readedData[] = $item;
        }

        $this->assertSame($etalonData, $readedData);
    }

    /**
     * Проверяет что объект читает корректно данные, если они не заданы.
     */
    public function testIteratorEmpty()
    {
        $mapper = new MockMapper('/root/firstLevel/secondLevel/realItem', [
            'thirdParam',
            'firstParam',
            'secondParam',
        ]);

        $reader = new Reader;
        $reader->setMapper($mapper)->openFile(__DIR__ . '/_fixture/test_empty.xml');

        $etalonData = include __DIR__ . '/_fixture/test_empty_result.php';
        $readedData = [];
        foreach ($reader as $item) {
            $readedData[] = $item;
        }

        $this->assertSame($etalonData, $readedData);
    }
}
