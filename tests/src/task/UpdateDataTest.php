<?php

declare(strict_types=1);

namespace marvin255\fias\tests\task;

use PHPUnit\DbUnit\DataSet\CompositeDataSet;
use marvin255\fias\task\UpdateData;
use marvin255\fias\task\RuntimeException;
use marvin255\fias\tests\DbTestCase;
use marvin255\fias\state\ArrayState;
use marvin255\fias\service\filesystem\Directory;
use marvin255\fias\service\xml\Reader;
use marvin255\fias\mapper\AbstractMapper;
use marvin255\fias\mapper\field;
use marvin255\fias\service\db\PdoConnection;
use InvalidArgumentException;

/**
 * Тест для объекта, который обновляет данные из ФИАС в БД.
 */
class UpdateDataTest extends DbTestCase
{
    /**
     * Проверяет, что объект создает таблицу и записывает все данные.
     */
    public function testRun()
    {
        $reader = new Reader;
        $mysql = new PdoConnection($this->getPdo(), 2);

        $tableName = 'testRun';

        $mapper = $this->getMockBuilder(AbstractMapper::class)
            ->setMethods([
                'getMap',
                'getSqlName',
                'getSqlPrimary',
                'getInsertFileMask',
                'getXmlPath',
                'extractArrayFromXml',
            ])
            ->getMock();
        $mapper->method('getMap')->will($this->returnValue([
            'id' => new field\IntNumber,
            'row1' => new field\Line,
        ]));
        $mapper->method('getSqlName')->will($this->returnValue($tableName));
        $mapper->method('getSqlPrimary')->will($this->returnValue(['id']));
        $mapper->method('getXmlPath')->will($this->returnValue('/root/item'));
        $mapper->method('getInsertFileMask')->will($this->returnValue('updateData_testRun_source.xml'));
        $mapper->method('extractArrayFromXml')->will($this->returnCallback(function ($xml) {
            $attributes = simplexml_load_string($xml)->attributes();
            $return = [
                'id' => isset($attributes['id']) ? (int) $attributes['id'] : 0,
                'row1' => isset($attributes['row1']) ? (string) $attributes['row1'] : '',
            ];

            return $return;
        }));

        $state = new ArrayState;
        $state->setParameter('extracted', new Directory(__DIR__ . '/_fixture'));

        $task = new UpdateData($reader, $mysql, $mapper);
        $task->run($state);

        $queryTable = $this->getConnection()->createQueryTable(
            $tableName,
            'SELECT * FROM ' . $tableName . ' ORDER BY id ASC'
        );
        $expectedTable = $this->createXmlDataSet(__DIR__ . '/_fixture/updateData_testRun_expected.xml')
            ->getTable($tableName);

        $this->assertTablesEqual($expectedTable, $queryTable);
    }

    /**
     * Проверяет, что если в объекте состояния не была передана директория, то
     * задача выбросит исключение.
     */
    public function testRunEmptyExtractedInStateException()
    {
        $reader = new Reader;
        $mysql = new PdoConnection($this->getPdo());
        $mapper = $this->getMockBuilder(AbstractMapper::class)->getMock();
        $state = new ArrayState;

        $task = new UpdateData($reader, $mysql, $mapper);

        $this->expectException(InvalidArgumentException::class);
        $task->run($state);
    }

    /**
     * Проверяет, что объект не внесет никаких изменений,
     * если не найдет файл для чтения.
     */
    public function testRunNoFileFound()
    {
        $reader = new Reader;
        $mysql = new PdoConnection($this->getPdo(), 2);

        $tableName = 'testRun';

        $mapper = $this->getMockBuilder(AbstractMapper::class)
            ->setMethods([
                'getMap',
                'getSqlName',
                'getSqlPrimary',
                'getInsertFileMask',
                'getXmlPath',
            ])
            ->getMock();
        $mapper->method('getMap')->will($this->returnValue([
            'id' => new field\IntNumber,
            'row1' => new field\Line,
        ]));
        $mapper->method('getSqlName')->will($this->returnValue($tableName));
        $mapper->method('getSqlPrimary')->will($this->returnValue(['id']));
        $mapper->method('getXmlPath')->will($this->returnValue('/root/item'));
        $mapper->method('getInsertFileMask')->will($this->returnValue('updateData_testRun_nothing.xml'));

        $state = new ArrayState;
        $state->setParameter('extracted', new Directory(__DIR__ . '/_fixture'));

        $task = new UpdateData($reader, $mysql, $mapper);
        $task->run($state);

        $queryTable = $this->getConnection()->createQueryTable(
            $tableName,
            'SELECT * FROM ' . $tableName
        );
        $expectedTable = $this->createXmlDataSet(__DIR__ . '/_fixture/updateData_testRun.xml')
            ->getTable($tableName);

        $this->assertTablesEqual($expectedTable, $queryTable);
    }

    /**
     * Проверяет, что объект выбросит исключение, если не сможет открыть
     * указанный файл.
     */
    public function testRunCantOpenFileException()
    {
        $reader = new Reader;
        $mysql = new PdoConnection($this->getPdo(), 2);

        $tableName = 'testRun';

        $mapper = $this->getMockBuilder(AbstractMapper::class)
            ->setMethods([
                'getMap',
                'getSqlName',
                'getSqlPrimary',
                'getXmlPath',
                'getInsertFileMask',
            ])
            ->getMock();
        $mapper->method('getMap')->will($this->returnValue([
            'id' => new field\IntNumber,
            'row1' => new field\Line,
        ]));
        $mapper->method('getSqlName')->will($this->returnValue($tableName));
        $mapper->method('getSqlPrimary')->will($this->returnValue(['id']));
        $mapper->method('getXmlPath')->will($this->returnValue('/badRoot/item'));
        $mapper->method('getInsertFileMask')->will($this->returnValue('updateData_testRun.xml'));

        $state = new ArrayState;
        $state->setParameter('extracted', new Directory(__DIR__ . '/_fixture'));

        $task = new UpdateData($reader, $mysql, $mapper);

        $this->expectException(RuntimeException::class);
        $task->run($state);
    }

    /**
     * @return \PHPUnit\DbUnit\DataSet\IDataSet
     */
    public function getDataSet()
    {
        $compositeDs = new CompositeDataSet;

        $compositeDs->addDataSet(
            $this->createXmlDataSet(__DIR__ . '/_fixture/updateData_testRun.xml')
        );

        return $compositeDs;
    }

    /**
     * Перед тестом накатываем структуру базы данных для тестов.
     */
    public static function setUpBeforeClass()
    {
        $pdo = self::getPdo();

        $pdo->exec('CREATE TABLE testRun (
            id int(11) not null,
            row1 varchar(30),
            PRIMARY KEY(id)
        )');

        return parent::setUpBeforeClass();
    }

    /**
     * После теста удаляем всю структуру, которая была создана во время теста.
     */
    public static function tearDownAfterClass()
    {
        $pdo = self::getPdo();
        $pdo->exec('DROP TABLE IF EXISTS testRun');

        return parent::tearDownAfterClass();
    }
}
