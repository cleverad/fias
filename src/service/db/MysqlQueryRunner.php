<?php

declare(strict_types=1);

namespace marvin255\fias\service\db;

use marvin255\fias\mapper\field;
use marvin255\fias\mapper\field\FieldInterface;
use PDO;
use PDOStatement;
use PDOException;

/**
 * Объект, который создает запросы для mysql.
 */
class MysqlQueryRunner
{
    /**
     * @var PDO
     */
    protected $pdoConnection;
    /**
     * Массив с подготовленными выражениями.
     *
     * Вносим сюда каждое подготовленное выражение, которое когда-либо было
     * создано данным объектом, чтобы не создавать кадый раз заново.
     *
     * @var PDOStatement[]
     */
    protected $preparedStatements = [];

    public function __construct(PDO $pdo)
    {
        $this->pdoConnection = $pdo;
    }

    /**
     * Метод, который вызывается перед вставкой большого объема данных. Отключает
     * автокоммиты, проверки уникальности и т.д.
     *
     * @return void
     */
    public function beginInsert()
    {
        $this->pdoConnection->exec('SET unique_checks=0');
        $this->pdoConnection->exec('SET foreign_key_checks=0');
    }

    /**
     * Метод, который вызывается после вставки большого объема данных. Включает
     * автокоммиты, проверки уникальности и т.д.
     *
     * @return void
     */
    public function completeInsert()
    {
        $this->pdoConnection->exec('SET foreign_key_checks=1');
        $this->pdoConnection->exec('SET unique_checks=1');
    }

    /**
     * Ищет строку в указанной таблице по условию.
     *
     * @param string   $table
     * @param array    $select
     * @param string[] $where
     *
     * @return array|null
     *
     * @throws Exception
     */
    public function selectRow(string $table, array $select, array $where = [])
    {
        $table = $this->escapeDDLName($table);
        $select = implode(', ', array_map([$this, 'escapeDDLName'], $select));

        $params = [];
        $sql = "SELECT {$select} FROM {$table}";
        if (!empty($where)) {
            list($where, $whereParams) = $this->makeWhereStatement($where);
            $sql .= " WHERE {$where}";
            $params = array_merge($params, $whereParams);
        }

        $res = $this->fetch($sql, $params);

        return !empty($res) ? reset($res) : null;
    }

    /**
     * Добавляет несколько строк в таблицу.
     *
     * @param string     $table
     * @param string[][] $insert
     *
     * @return void
     *
     * @throws Exception
     */
    public function batchInsert(string $table, array $insert)
    {
        $table = $this->escapeDDLName($table);
        $firstItem = reset($insert);
        $fields = array_keys($firstItem);

        $setOfFields = implode(', ', array_map([$this, 'escapeDDLName'], $fields));
        $setOfValues = implode(', ', array_fill(0, count($fields), '?'));
        $allValues = implode('), (', array_fill(0, count($insert), $setOfValues));
        $sqlForBulkInsert = "INSERT INTO {$table} ({$setOfFields}) VALUES ({$allValues})";

        $flatAray = call_user_func_array('array_merge', array_map('array_values', $insert));

        $this->execute($sqlForBulkInsert, $flatAray);
    }

    /**
     * Обновляет строку в базе данных.
     *
     * @return void
     *
     * @throws Exception
     */
    public function update(string $table, array $update, array $where = [])
    {
        $table = $this->escapeDDLName($table);
        list($set, $params) = $this->makeSetStatement($update);

        $sql = "UPDATE {$table} SET {$set}";
        if (!empty($where)) {
            list($where, $whereParams) = $this->makeWhereStatement($where);
            $sql .= " WHERE {$where}";
            $params = array_merge($params, $whereParams);
        }

        $this->execute($sql, $params);
    }

    /**
     * Обновляет строку в базе данных.
     *
     * @return void
     *
     * @throws Exception
     */
    public function delete(string $table, array $where = [])
    {
        $table = $this->escapeDDLName($table);

        $params = [];
        $sql = "DELETE FROM {$table}";
        if (!empty($where)) {
            list($where, $whereParams) = $this->makeWhereStatement($where);
            $sql .= " WHERE {$where}";
            $params = array_merge($params, $whereParams);
        }

        $this->execute($sql, $params);
    }

    /**
     * Создает таблицу в БД.
     *
     * @param string           $table
     * @param FieldInterface[] $fields
     * @param string[]         $primaries
     * @param string[][]       $indexes
     * @param string           $partitionField
     * @param int              $partitionCount
     *
     * @return void
     *
     * @throws Exception
     */
    public function createTable(
        string $table,
        array $fields,
        array $primaries,
        array $indexes = [],
        string $partitionField = '',
        int $partitionCount = 1
    ) {
        $definitionParts = [
            $this->makeFieldsForCreateTable($fields),
            $this->makeIndexListForCreateTable($indexes),
            $this->makePrimaryForCreateTable($primaries),
        ];

        $table = $this->escapeDDLName($table);
        $definition = implode(', ', array_diff($definitionParts, ['']));
        $meta = $this->makeMetaForCreateTable($partitionField, $partitionCount);

        $sql = "CREATE TABLE {$table} ({$definition}) {$meta}";

        $this->execute($sql);
    }

    /**
     * Удаляет таблицу из БД.
     *
     * @return void
     *
     * @throws Exception
     */
    public function dropTable(string $table)
    {
        $table = $this->escapeDDLName($table);
        $this->execute("DROP TABLE IF EXISTS {$table}");
    }

    /**
     * Выполняет запрос с указанными параметрами.
     *
     * @throws Exception
     */
    protected function execute(string $sql, array $params = []): PDOStatement
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

        return $statement;
    }

    /**
     * Выполняет запрос с указанными параметрами и возвращает значения в виде
     * ассоциативного массива.
     *
     * @throws Exception
     */
    protected function fetch(string $sql, array $params = []): array
    {
        return $this->execute($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Возвращает подговтовленное выражение, если оно уже есть,
     * либо создает новое и добавляет в список.
     *
     * @throws Exception
     */
    protected function getStatement(string $sql): PDOStatement
    {
        foreach ($this->preparedStatements as $prepared) {
            if ($prepared->queryString === $sql) {
                return $prepared;
            }
        }

        $newPrepared = $this->pdoConnection->prepare($sql);
        if (!($newPrepared instanceof PDOStatement)) {
            throw new Exception("Can't prepare statement for {$sql}");
        }

        $this->preparedStatements[] = $newPrepared;

        return $newPrepared;
    }

    /**
     * Эскейпит названия колонок и таблиц.
     */
    protected function escapeDDLName(string $name): string
    {
        return '`' . trim(str_replace('`', '', $name)) . '`';
    }

    /**
     * Создает условие для поиска из массива значений.
     *
     * Имена колонок берет из ключей, значения из значений, в качестве оператора
     * использует =, условия объединяет через AND. Возврашает строку с условием
     * и массив параметров для биндинга.
     */
    protected function makeWhereStatement(array $condition): array
    {
        $names = array_map([$this, 'escapeDDLName'], array_keys($condition));
        $sql = implode('= ? AND ', $names) . ' = ?';
        $params = array_values($condition);

        return [$sql, $params];
    }

    /**
     * Создает выражение для оператора set.
     *
     * Имена колонок берет из ключей, значения из значений, в качестве оператора
     * использует =, условия объединяет через AND. Возврашает строку с условием
     * и массив параметров для биндинга.
     */
    protected function makeSetStatement(array $set): array
    {
        $names = array_map([$this, 'escapeDDLName'], array_keys($set));
        $sql = implode('= ?, ', $names) . ' = ?';
        $params = array_values($set);

        return [$sql, $params];
    }

    /**
     * Создает строку с описаниями индексов для CREATE TABLE.
     *
     * @param string[][] $indexes
     *
     * @return string
     */
    protected function makeIndexListForCreateTable(array $indexes): string
    {
        $return = '';

        foreach ($indexes as $indexKey => $arIndex) {
            $arIndex = array_map([$this, 'escapeDDLName'], $arIndex);
            $return .= $return ? ', ' : '';
            $return .= 'INDEX ' . $this->escapeDDLName("ndx_{$indexKey}");
            $return .= ' (' . implode(', ', $arIndex) . ')';
        }

        return $return;
    }

    /**
     * Создает строку с описанием полей для CREATE TABLE.
     *
     * @param FieldInterface[] $fields
     *
     * @return string
     */
    protected function makeFieldsForCreateTable(array $fields): string
    {
        $return = '';

        foreach ($fields as $fieldName => $field) {
            $fieldName = $this->escapeDDLName($fieldName);
            $paramType = $this->resolveFieldForCreateTable($field);
            $return .= $return ? ', ' : '';
            $return .= "{$fieldName} {$paramType}";
        }

        return $return;
    }

    /**
     * Возвращает строку для создания колонки из типа поля.
     *
     * @param FieldInterface $field
     *
     * @return string
     *
     * @throws Exception
     */
    protected function resolveFieldForCreateTable(FieldInterface $field): string
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
     * Создает строку с описанием первичного ключа для CREATE TABLE.
     *
     * @return string
     */
    protected function makePrimaryForCreateTable(array $primary): string
    {
        $arPrimary = array_map([$this, 'escapeDDLName'], $primary);

        return 'PRIMARY KEY (' . implode(', ', $arPrimary) . ')';
    }

    /**
     * Создает строку с описанием первичного ключа для CREATE TABLE.
     *
     * @return string
     */
    protected function makeMetaForCreateTable(string $partitionField, int $partitionCount): string
    {
        $meta = 'ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci';
        if ($partitionCount > 1 && !empty($partitionField)) {
            $meta .= ' PARTITION BY KEY(' . $this->escapeDDLName($partitionField) . ')';
            $meta .= " PARTITIONS {$partitionCount}";
        }

        return $meta;
    }
}
