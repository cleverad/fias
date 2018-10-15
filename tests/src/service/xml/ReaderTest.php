<?php

declare(strict_types=1);

namespace marvin255\fias\tests\service\xml;

use marvin255\fias\tests\BaseTestCase;
use marvin255\fias\service\xml\Reader;
use InvalidArgumentException;
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
    public function testOpenFile()
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
    public function testOpenFileNoMapperException()
    {
        $reader = new Reader;

        $this->expectException(RuntimeException::class);
        $reader->openFile(__DIR__ . '/_fixture/test_open_true.xml');
    }

    /**
     * Проверяет, что при попытке открыть несуществующий файл будет выброшено
     * исключение.
     */
    public function testOpenFileUnexistedFileException()
    {
        $mapper = new MockMapper('/root/firstLevel/secondLevel/realItem', [
            'thirdParam',
            'firstParam',
            'secondParam',
        ]);

        $reader = new Reader;
        $reader->setMapper($mapper);

        $this->expectException(InvalidArgumentException::class);
        $reader->openFile(__DIR__ . '/_fixture/test_unexisted.xml');
    }

    /**
     * Проверяет, что при попытке итерировать по объекту без открытого файла
     * будет выброшено исключение.
     */
    public function testIteratorNotOpenException()
    {
        $reader = new Reader;

        $this->expectException(RuntimeException::class);
        foreach ($reader as $item) {
        }
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
        $readedData = [];
        foreach ($reader as $key => $item) {
            $readedData[$key] = $item;
        }

        $this->assertSame($etalonData, $readedData);
    }

    /**
     * Проверяет что объект читает корректно пустой массив данных.
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
        $readedData = [];
        foreach ($reader as $key => $item) {
            $readedData[$key] = $item;
        }

        $this->assertSame($etalonData, $readedData);
    }

    /**
     * Проверяет, что метод current корректно возвращает данные, если вызывается
     * не через foreach.
     */
    public function testCurrent()
    {
        $mapper = new MockMapper('/root/firstLevel/secondLevel/realItem', [
            'thirdParam',
            'firstParam',
            'secondParam',
        ]);

        $reader = new Reader;
        $reader->setMapper($mapper)->openFile(__DIR__ . '/_fixture/test_iterator.xml');

        $etalonData = include __DIR__ . '/_fixture/test_iterator_result.php';
        $reader->current();
        $reader->current();
        $this->assertSame(reset($etalonData), $reader->current());
    }

    /**
     * Проверяет, что метод current выбросит исключение, если файл не был
     * открыт.
     */
    public function testCurrentNotOpenedException()
    {
        $mapper = new MockMapper('/root/firstLevel/secondLevel/realItem', [
            'thirdParam',
            'firstParam',
            'secondParam',
        ]);

        $reader = new Reader;
        $reader->setMapper($mapper);

        $this->expectException(RuntimeException::class);
        $reader->current();
    }
}
