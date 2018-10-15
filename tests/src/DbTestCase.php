<?php

declare(strict_types=1);

namespace marvin255\fias\tests;

use PHPUnit\DbUnit\Database\Connection;
use PHPUnit\DbUnit\TestCaseTrait;
use PDO;
use InvalidArgumentException;

/**
 * Абстрактный класс для тестов, которые используют базу данных.
 */
abstract class DbTestCase extends BaseTestCase
{
    use TestCaseTrait;

    /**
     * @var \PDO|null
     */
    private static $pdo;
    /**
     * @var \PHPUnit\DbUnit\Database\Connection|null
     */
    private $connection;

    /**
     *  Возвращает объект для тестирования базы данных.
     *
     * @return \PHPUnit\DbUnit\Database\Connection
     */
    final public function getConnection(): Connection
    {
        if ($this->connection === null) {
            $this->connection = $this->createDefaultDBConnection($this->getPdo(), ':memory:');
        }

        return $this->connection;
    }

    /**
     * Возвращает объект PDO для соединения с базой данных.
     *
     * @return \PDO
     */
    protected function getPdo(): PDO
    {
        if (self::$pdo == null) {
            self::$pdo = new PDO('sqlite::memory:');
        }

        return self::$pdo;
    }

    /**
     * Проверяет, что таблица существует.
     *
     * @param string $tableName
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    protected function assertTableExists(string $tableName)
    {
        if (!preg_match('/^[a-zA-Z0-9_]{3,}$/', $tableName)) {
            throw new InvalidArgumentException("Wrong table name {$tableName}");
        }

        $statement = $this->getPdo()->prepare('SELECT name FROM sqlite_master WHERE type = \'table\' AND name = :table');
        $statement->bindParam(':table', $tableName);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        $this->assertThat(
            $result,
            $this->identicalTo(['name' => $tableName]),
            "Table {$tableName} not found"
        );
    }

    /**
     * Проверяет, что таблицы не существует.
     *
     * @param string $tableName
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    protected function assertTableNotExists(string $tableName)
    {
        if (!preg_match('/^[a-zA-Z0-9_]{3,}$/', $tableName)) {
            throw new InvalidArgumentException("Wrong table name {$tableName}");
        }

        $statement = $this->getPdo()->prepare('SELECT name FROM sqlite_master WHERE type = \'table\' AND name = :table');
        $statement->bindParam(':table', $tableName);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        $this->assertEmpty($result, "Table {$tableName} found");
    }

    /**
     * Проверяет, что колонка в таблице существует.
     *
     * @param string $tableName
     * @param string $colName
     * @param string $type
     *
     * @throws \InvalidArgumentException
     */
    protected function assertTableColExists(string $tableName, string $colName, string $type)
    {
        if (!preg_match('/^[a-zA-Z0-9_]{3,}$/', $tableName)) {
            throw new InvalidArgumentException("Wrong table name {$tableName}");
        }

        if (!preg_match('/^[a-zA-Z0-9_]{3,}$/', $colName)) {
            throw new InvalidArgumentException("Wrong column name {$colName}");
        }

        if (!preg_match('/^[a-zA-Z0-9_]{3,}$/', $type)) {
            throw new InvalidArgumentException("Wrong type name {$type}");
        }

        $statement = $this->getPdo()->prepare('SELECT sql FROM sqlite_master WHERE type = \'table\' AND name = :table');
        $statement->bindParam(':table', $tableName);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        $this->assertThat(
            !empty($result['sql'])
                ? str_replace(["\r", "\n", "\t"], ' ', $result['sql'])
                : '',
            $this->matchesRegularExpression("/^.*{$tableName}.*\(.*{$colName}[^\(\),]+{$type}.*\).*$/iu"),
            "Column {$colName} with type {$type} not found in table {$tableName}"
        );
    }
}
