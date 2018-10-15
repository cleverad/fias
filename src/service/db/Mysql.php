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
     * Задает объект PDO для соединения с базой данных.
     *
     * @param \PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdoConnection = $pdo;
    }

    /**
     * @inheritdoc
     */
    public function insert(SqlMapperInterface $mapper, array $item)
    {
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
        $fields = '';
        foreach ($mapper->getMap() as $fieldName => $field) {
            $fieldName = $this->escapeDDLName($fieldName);
            $paramType = $this->resolveParamType($field);
            $fields .= "{$fieldName} {$paramType}, ";
        }

        $primary = '';
        foreach ($mapper->getSqlPrimary() as $fieldName) {
            $fieldName = $this->escapeDDLName($fieldName);
            $primary .= $primary ? ", {$fieldName}" : $fieldName;
        }
        $primary = "PRIMARY KEY({$primary})";

        $tableName = $this->escapeDDLName($mapper->getSqlName());
        $sql = "CREATE TABLE {$tableName} ({$fields}{$primary})";

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
    }

    /**
     * Выполняет запрос с указанными параметрами.
     *
     * @param string $sql
     * @param array  $params
     *
     * @throws \marvin255\fias\service\db\Exception
     */
    public function execute(string $sql, array $params = []): bool
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
        } elseif ($field instanceof field\IntNumber) {
            $return = 'int(' . $field->getLength() . ') not null';
        } elseif ($field instanceof field\Date) {
            $return = 'date not null';
        } else {
            throw new Exception(
                "Can't resolve " . get_class($field) . ' to mysql type'
            );
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
     */
    protected function createPrimaryCondition(SqlMapperInterface $mapper, array $item)
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
