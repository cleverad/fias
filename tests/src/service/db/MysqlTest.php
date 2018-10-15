<?php

declare(strict_types=1);

namespace marvin255\fias\tests\service\db;

use marvin255\fias\tests\DbTestCase;
use marvin255\fias\mapper\SqlMapperInterface;
use marvin255\fias\mapper\field;
use marvin255\fias\service\db\Mysql;
use PHPUnit\DbUnit\DataSet\CompositeDataSet;

/**
 * Тест для объекта, который взаймодействует с базой данных mysql.
 */
class MysqlTest extends DbTestCase
{
    /**
     * Проверяет, что объект создает таблицу как указано в маппере.
     */
    public function testCreateTable()
    {
        $tableName = 'testCreateTable';
        $columnsNames = [
            'col_' . $this->faker()->unique()->word,
            'col_' . $this->faker()->unique()->word,
            'col_' . $this->faker()->unique()->word,
        ];
        $columnsDefinitions = [
            new field\IntNumber,
            new field\Line,
            new field\Date,
        ];
        $columns = array_combine($columnsNames, $columnsDefinitions);

        $mapper = $this->getMockBuilder(SqlMapperInterface::class)
            ->getMock();
        $mapper->method('getMap')->will($this->returnValue($columns));
        $mapper->method('getSqlName')->will($this->returnValue($tableName));
        $mapper->method('getSqlPrimary')->will($this->returnValue([reset($columnsNames)]));
        $mapper->method('getSqlIndexes')->will($this->returnValue([]));
        $mapper->method('getSqlPartitionsCount')->will($this->returnValue(1));
        $mapper->method('getSqlPartitionField')->will($this->returnValue(''));

        $mysql = new Mysql($this->getPdo());
        $mysql->createTable($mapper);

        $this->assertTableExists($tableName);
        $this->assertTableColExists($tableName, $columnsNames[0], 'int');
        $this->assertTableColExists($tableName, $columnsNames[1], 'varchar');
        $this->assertTableColExists($tableName, $columnsNames[2], 'date');
    }

    /**
     * Проверяет, что объект удаляет таблицу, указанную в маппере.
     */
    public function testDropTable()
    {
        $tableName = 'testDropTable';

        $mapper = $this->getMockBuilder(SqlMapperInterface::class)
            ->getMock();
        $mapper->method('getSqlName')->will($this->returnValue($tableName));

        $mysql = new Mysql($this->getPdo());

        $this->assertTableExists($tableName);
        $mysql->dropTable($mapper);
        $this->assertTableNotExists($tableName);
    }

    /**
     * @return \PHPUnit\DbUnit\DataSet\IDataSet
     */
    public function getDataSet()
    {
        $compositeDs = new CompositeDataSet;

        return $compositeDs;
    }

    /**
     * Перед тестом накатываем структуру базы данных для тестов.
     */
    public function setUp()
    {
        $pdo = $this->getPdo();

        $pdo->exec('CREATE TABLE testDropTable (
            id int(11) not null,
            row1 varchar(30),
            row2 varchar(30),
            PRIMARY KEY(id)
        )');

        return parent::setUp();
    }

    /**
     * После теста удаляем всю структуру, которая была создана во время теста.
     */
    public function tearDown()
    {
        $this->getPdo()->exec('DROP TABLE IF EXISTS testCreateTable');
        $this->getPdo()->exec('DROP TABLE IF EXISTS testDropTable');

        return parent::tearDown();
    }
}
