<?php

declare(strict_types=1);

namespace marvin255\fias\service\db;

use marvin255\fias\mapper\SqlMapperInterface;
use marvin255\fias\mapper\FieldInterface;
use marvin255\fias\mapper\field;
use PDO;
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
    }

    /**
     * @inheritdoc
     */
    public function delete(SqlMapperInterface $mapper, array $item)
    {
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
            $statement = $this->pdoConnection->prepare($sql);
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
}
