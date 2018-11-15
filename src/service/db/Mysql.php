<?php

declare(strict_types=1);

namespace marvin255\fias\service\db;

use marvin255\fias\mapper\SqlMapperInterface;
use marvin255\fias\mapper\FieldInterface;
use marvin255\fias\mapper\field;
use PDO;
use PDOStatement;
use PDOException;

/**
 * Объект для взаймодействия с базой данных mysql.
 */
class Mysql implements DbInterface
{
    /**
     * @var \PDO
     */
    protected $pdoConnection;
    /**
     * @var array
     */
    protected $prepared = [];
    /**
     * @var mixed[]
     */
    protected $insertQueue = [];
    /**
     * @var int
     */
    protected $butchInsertLimit = 50;

    /**
     * Задает объект PDO для соединения с базой данных.
     *
     * @param \PDO $pdo
     * @param int  $butchInsertLimit
     */
    public function __construct(PDO $pdo, int $butchInsertLimit = 50)
    {
        $this->pdoConnection = $pdo;
        $this->butchInsertLimit = $butchInsertLimit;
    }

    /**
     * @inheritdoc
     */
    public function insert(SqlMapperInterface $mapper, array $item)
    {
        $table = $mapper->getSqlName();

        if (!isset($this->insertQueue[$table])) {
            $this->insertQueue[$table] = [];
        }

        $this->insertQueue[$table][] = $mapper->mapArray($item);

        if (count($this->insertQueue[$table]) === $this->butchInsertLimit) {
            $this->flushInsert($table);
        }
    }

    /**
     * @inheritdoc
     */
    public function update(SqlMapperInterface $mapper, array $item)
    {
        list($where, $params) = $this->createPrimaryCondition($mapper, $item);

        $set = '';
        $setCount = 0;
        foreach ($mapper->getMap() as $fieldName => $field) {
            if (isset($item[$fieldName]) && !in_array($fieldName, $mapper->getSqlPrimary())) {
                $paramName = ":set{$setCount}";
                $fieldNameEscaped = $this->escapeDDLName($fieldName);
                $set .= ($set ? ', ' : '') . "{$fieldNameEscaped} = {$paramName}";
                $params[$paramName] = $field->convertToString($item[$fieldName]);
                ++$setCount;
            }
        }

        $table = $this->escapeDDLName($mapper->getSqlName());
        $sql = "UPDATE {$table} SET {$set} WHERE {$where}";

        $this->execute($sql, $params);
    }

    /**
     * @inheritdoc
     */
    public function delete(SqlMapperInterface $mapper, array $item)
    {
        list($where, $params) = $this->createPrimaryCondition($mapper, $item);

        $sql = 'DELETE FROM ' . $this->escapeDDLName($mapper->getSqlName()) . " WHERE {$where}";

        $this->execute($sql, $params);
    }

    /**
     * @inheritdoc
     */
    public function createTable(SqlMapperInterface $mapper)
    {
        $fields = $this->createFieldListForCreateTable($mapper->getMap());

        $index = $this->createIndexListForCreateTable($mapper->getSqlIndexes());

        $arPrimary = array_map([$this, 'escapeDDLName'], $mapper->getSqlPrimary());
        $primary = 'PRIMARY KEY (' . implode(', ', $arPrimary) . ')';

        $afterTable = ' ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci';
        if ($mapper->getSqlPartitionsCount() > 1 && $mapper->getSqlPartitionField()) {
            $afterTable .= ' PARTITION BY HASH(' . $this->escapeDDLName($mapper->getSqlPartitionField()) . ')';
            $afterTable .= ' PARTITIONS ' . $mapper->getSqlPartitionsCount();
        }

        $tableName = $this->escapeDDLName($mapper->getSqlName());
        $sql = "CREATE TABLE {$tableName} ({$fields}{$index}{$primary})";
        if ($this->pdoConnection->getAttribute(PDO::ATTR_DRIVER_NAME) !== 'sqlite') {
            $sql .= $afterTable;
        }

        $this->execute($sql);
    }

    /**
     * @inheritdoc
     */
    public function dropTable(SqlMapperInterface $mapper)
    {
        $tableName = $this->escapeDDLName($mapper->getSqlName());
        $this->execute("DROP TABLE IF EXISTS {$tableName}");
    }

    /**
     * @inheritdoc
     */
    public function truncateTable(SqlMapperInterface $mapper)
    {
        $tableName = $this->escapeDDLName($mapper->getSqlName());
        $this->execute("DELETE FROM {$tableName}");
    }

    /**
     * @inheritdoc
     */
    public function complete()
    {
        foreach ($this->insertQueue as $table => $items) {
            $this->flushInsert($table);
        }
    }

    /**
     * Отправляет очередь insert запросов.
     *
     * @param string $table
     *
     * @throws \marvin255\fias\service\db\Exception
     *
     * @return void
     */
    protected function flushInsert(string $table)
    {
        $data = $this->insertQueue[$table];
        $firstItem = reset($data);
        $fields = array_keys($firstItem);
        $escapedTable = $this->escapeDDLName($table);
        unset($this->insertQueue[$table]);

        $setOfFields = implode(', ', array_map([$this, 'escapeDDLName'], $fields));
        $setOfValues = implode(', ', array_fill(0, count($fields), '?'));
        $sqlForBulkInsert = "INSERT INTO {$escapedTable} ({$setOfFields}) VALUES ("
            . implode('), (', array_fill(0, count($data), $setOfValues))
            . ')';
        $flatAray = call_user_func_array('array_merge', array_map('array_values', $data));

        $this->execute($sqlForBulkInsert, $flatAray);
    }

    /**
     * Выполняет запрос с указанными параметрами.
     *
     * @param string $sql
     * @param array  $params
     *
     * @throws \marvin255\fias\service\db\Exception
     */
    protected function execute(string $sql, array $params = []): bool
    {
        try {
            $statement = $this->getStatement($sql);
            $res = $statement->execute($params);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(), 0, $e);
        }

        if (!$res) {
            $error = $statement->errorInfo();
            throw new Exception($error[2]);
        }

        return $res;
    }

    /**
     * Возвращает подговтовленное выражение, если оно уже есть,
     * либо создает новое и добавляет в список.
     *
     * @param string $sql
     *
     * @return \PDOStatement
     *
     * @throws \marvin255\fias\service\db\Exception
     */
    protected function getStatement(string $sql): PDOStatement
    {
        foreach ($this->prepared as $prepared) {
            if ($prepared->queryString === $sql) {
                return $prepared;
            }
        }

        $newPrepared = $this->pdoConnection->prepare($sql);
        if (!($newPrepared instanceof PDOStatement)) {
            throw new Exception("Can't prepare statement for {$sql}");
        }

        $this->prepared[] = $newPrepared;

        return $newPrepared;
    }

    /**
     * Создает строку с описаниями индексов для CREATE TABLE.
     *
     * @param string[][] $fields
     *
     * @return string
     */
    protected function createIndexListForCreateTable(array $indexes): string
    {
        $return = '';

        foreach ($indexes as $indexKey => $arIndex) {
            $arIndex = array_map([$this, 'escapeDDLName'], $arIndex);
            $return .= 'INDEX ' . $this->escapeDDLName("ndx_{$indexKey}");
            $return .= ' (' . implode(', ', $arIndex) . '), ';
        }

        return $return;
    }

    /**
     * Создает строку с описаниями столбцов для CREATE TABLE.
     *
     * @param FieldInterface[] $fields
     *
     * @return string
     */
    protected function createFieldListForCreateTable(array $fields): string
    {
        $return = '';

        foreach ($fields as $fieldName => $field) {
            $fieldName = $this->escapeDDLName($fieldName);
            $paramType = $this->resolveParamType($field);
            $return .= "{$fieldName} {$paramType}, ";
        }

        return $return;
    }

    /**
     * Возвращает строку для создания колонки из типа поля.
     *
     * @param \marvin255\fias\mapper\FieldInterface $field
     *
     * @return string
     *
     * @throws \marvin255\fias\service\db\Exception
     */
    protected function resolveParamType(FieldInterface $field): string
    {
        $return = '';

        if ($field instanceof field\Line) {
            $return = 'varchar(' . $field->getLength() . ') not null';
        }

        if ($field instanceof field\IntNumber) {
            $return = 'int(' . $field->getLength() . ') not null';
        }

        if ($field instanceof field\Date) {
            $return = 'date not null';
        }

        return $return;
    }

    /**
     * Эскейпит названия колонок и таблиц.
     *
     * @param string $name
     *
     * @return string
     */
    protected function escapeDDLName(string $name): string
    {
        return '`' . trim(str_replace('`', '', $name)) . '`';
    }

    /**
     * Создает условие для поиска строк по первичному ключу.
     *
     * @param \marvin255\fias\mapper\SqlMapperInterface $mapper
     * @param array                                     $item
     *
     * @throws \marvin255\fias\service\db\Exception
     *
     * @return mixed[]
     */
    protected function createPrimaryCondition(SqlMapperInterface $mapper, array $item): array
    {
        $sql = '';
        $params = [];
        $primaryCount = 0;
        foreach ($mapper->getSqlPrimary() as $primaryName) {
            if (!isset($item[$primaryName])) {
                throw new Exception("There is no {$primaryName} key in item");
            }
            $escapedName = $this->escapeDDLName($primaryName);
            $primaryParam = ":primary{$primaryCount}";
            $sql .= ($sql ? ' AND ' : '') . "{$escapedName} = {$primaryParam}";
            $params[$primaryParam] = $item[$primaryName];
            ++$primaryCount;
        }

        return [$sql, $params];
    }
}
