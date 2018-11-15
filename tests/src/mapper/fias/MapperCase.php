<?php

declare(strict_types=1);

namespace marvin255\fias\tests\mapper\fias;

use marvin255\fias\tests\BaseTestCase;
use marvin255\fias\mapper\MapperInterface;
use RuntimeException;

/**
 * Базовый тест кейс для мапперов.
 */
abstract class MapperCase extends BaseTestCase
{
    /**
     * Возвращает данные для проверки извлечения из xml.
     */
    abstract protected function getTestData(): array;

    /**
     * Возвращает строку с xml на основании входного параметра.
     */
    abstract protected function getTestXml(array $data): string;

    /**
     * Возвращает объект маппера.
     */
    abstract protected function getMapper(): MapperInterface;

    /**
     * Проверяет, что маппер правильно извлекает результат из xml.
     */
    public function testExtractArrayFromXml()
    {
        $data = $this->getTestData();
        $xml = $this->getTestXml($data);

        $xmlData = $this->getMapper()->extractArrayFromXml($xml);

        ksort($xmlData);
        ksort($data);

        $this->assertEquals($data, $xmlData);
    }

    /**
     * Проверяет, что маппер выбрасывает исключение при неверно заданном xml.
     */
    public function testExtractArrayFromXmlParsingException()
    {
        $this->expectException(RuntimeException::class);
        $this->getMapper()->extractArrayFromXml('<= 123>');
    }

    /**
     * Проверяет, что маппер выделяет из входящего массива только те элементы,
     * ключи для которых описаны в списке полей маппера.
     */
    public function testMapArray()
    {
        $data = $this->getTestData();

        $messyData = array_merge($data, [
            $this->faker()->word => $this->faker()->word,
            $this->faker()->word => $this->faker()->word,
            $this->faker()->word => $this->faker()->word,
        ]);
        $mappedData = $this->getMapper()->mapArray($messyData);

        ksort($data);
        ksort($mappedData);

        $this->assertSame($data, $mappedData);
    }

    /**
     * Проверяет, что маппер выделяет из входящего массива только те элементы,
     * ключи для которых описаны в списке полей маппера, и конвертирует их
     * в строковое представление.
     */
    public function testConvertToStrings()
    {
        $mapper = $this->getMapper();
        $fields = $mapper->getMap();
        $data = $this->getTestData();

        $mappedData = $mapper->convertToStrings($data);

        foreach ($data as $key => $value) {
            $data[$key] = $fields[$key]->convertToString($value);
        }

        ksort($data);
        ksort($mappedData);

        $this->assertSame($data, $mappedData);
    }

    /**
     * Проверяет, что маппер выделяет их входящего массива только те элементы,
     * ключи для которых описаны в списке первичных ключей.
     */
    public function testMapPrimaries()
    {
        $mapper = $this->getMapper();
        $primaries = $mapper->getSqlPrimary();
        $data = $this->getTestData();

        $mappedData = $mapper->mapPrimaries($data);

        foreach ($data as $key => $value) {
            if (!in_array($key, $primaries)) {
                unset($data[$key]);
            }
        }

        ksort($data);
        ksort($mappedData);

        $this->assertSame($data, $mappedData);
    }

    /**
     * Проверяет, что маппер выделяет их входящего массива только те элементы,
     * ключи для которых не описаны в списке первичных ключей.
     */
    public function testMapNotPrimaries()
    {
        $mapper = $this->getMapper();
        $primaries = $mapper->getSqlPrimary();
        $data = $this->getTestData();

        $mappedData = $mapper->mapNotPrimaries($data);

        foreach ($data as $key => $value) {
            if (in_array($key, $primaries)) {
                unset($data[$key]);
            }
        }

        ksort($data);
        ksort($mappedData);

        $this->assertSame($data, $mappedData);
    }

    /**
     * Проверяет, что объект возвращает имя таблицы для sql.
     */
    public function testGetSqlName()
    {
        $this->assertRegExp(
            '#[a-z]+[a-z0-9_]*#',
            $this->getMapper()->getSqlName()
        );
    }

    /**
     * Проверяет, что объект возвращает не пустой, валидный xpath
     * для поиска данных в файле xml.
     */
    public function testGetXmlPath()
    {
        $this->assertRegExp(
            '#/[a-zA-Z0-9_]+[a-zA-Z0-9_/]*#',
            $this->getMapper()->getXmlPath()
        );
    }

    /**
     * Проверяет, что объект возвращает не пустую валидную маску для поиска
     * файла для вставки.
     */
    public function testGetInsertFileMask()
    {
        $this->assertRegExp(
            '#[a-zA-Z0-9_\*\.]+#',
            $this->getMapper()->getInsertFileMask()
        );
    }

    /**
     * Проверяет, что объект возвращает не пустую валидную маску для поиска
     * файла для удаления.
     */
    public function testGetDeleteFileMask()
    {
        $this->assertRegExp(
            '#[a-zA-Z0-9_\*\.]+#',
            $this->getMapper()->getDeleteFileMask()
        );
    }

    /**
     * Проверяет, что маппер возвращает первичный ключ.
     */
    public function testGetSqlPrimary()
    {
        $mapper = $this->getMapper();
        $fields = array_keys($mapper->getMap());

        $this->assertNotEmpty($mapper->getSqlPrimary());
        foreach ($mapper->getSqlPrimary() as $primary) {
            $this->assertContains($primary, $fields);
        }
    }

    /**
     * Проверяет, что маппер возвращает валидные индексы.
     */
    public function testGetSqlIndexes()
    {
        $mapper = $this->getMapper();
        $fields = array_keys($mapper->getMap());

        $indexes = $mapper->getSqlIndexes();

        $this->assertInternalType('array', $indexes);
        foreach ($indexes as $index) {
            $this->assertInternalType('array', $index);
            foreach ($index as $indexPart) {
                $this->assertContains($indexPart, $fields);
            }
        }
    }

    /**
     * Проверяет, что объект возвращает число частей для разделения таблицы >= 1.
     */
    public function testGetSqlPartitionsCount()
    {
        $this->assertNotEmpty($this->getMapper()->getSqlPartitionsCount());
    }

    /**
     * Проверяет, что маппер возвращает валидные поля для разделения таблицы на части.
     */
    public function testGetSqlPartitionsFields()
    {
        $mapper = $this->getMapper();
        $fields = array_keys($mapper->getMap());

        $partitionField = $mapper->getSqlPartitionField();

        $this->assertInternalType('string', $partitionField);
        if ($partitionField !== '') {
            $this->assertContains($partitionField, $fields);
        }
    }
}
