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
     * Возвращает объект маппера.
     */
    abstract protected function getMapper(): MapperInterface;

    /**
     * Возвращает данные для проверки извлечения из xml.
     */
    abstract protected function getXmlTestData(): array;

    /**
     * Проверяет, что маппер правильно извлекает результат из xml.
     */
    public function testExtractArrayFromXml()
    {
        list($data, $xml) = $this->getXmlTestData();

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
     * Проверяет, что объект возвращает имя таблицы для sql.
     */
    public function testGetSqlName()
    {
        $this->assertNotEmpty($this->getMapper()->getSqlName());
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

        $partitionFields = $mapper->getSqlPartitionsFields();

        $this->assertInternalType('array', $partitionFields);
        foreach ($partitionFields as $partitionField) {
            $this->assertContains($partitionField, $fields);
        }
    }
}
