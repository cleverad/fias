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
            $this->connection = $this->createDefaultDBConnection(self::getPdo(), ':memory:');
        }

        return $this->connection;
    }

    /**
     * Возвращает объект PDO для соединения с базой данных.
     *
     * @return \PDO
     */
    protected static function getPdo(): PDO
    {
        if (self::$pdo == null) {
            self::$pdo = new PDO(PHPUNIT_PDO_DSN, PHPUNIT_PDO_USER, PHPUNIT_PDO_PASSWORD, PHPUNIT_PDO_ATTRIBUTES);
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

        $statement = $this->getPdo()->prepare('SHOW TABLES LIKE :table');
        $statement->bindParam(':table', $tableName);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        $this->assertThat(
            $result,
            $this->logicalAnd(
                $this->isType('array'),
                $this->contains($tableName)
            ),
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

        $statement = $this->getPdo()->prepare('SHOW TABLES LIKE :table');
        $statement->bindParam(':table', $tableName);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        $this->assertThat(
            $result,
            $this->logicalOr(
                $this->logicalNot($this->isType('array')),
                $this->logicalNot($this->contains($tableName))
            ),
            "Table {$tableName} found"
        );
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

        $statement = $this->getPdo()->prepare("SHOW COLUMNS FROM {$tableName} LIKE :column");
        $statement->bindParam(':column', $colName);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        $assert = isset($result['Field'], $result['Type'])
            && $result['Field'] === $colName
            && strpos($result['Type'], $type) === 0
        ;

        $this->assertThat(
            $assert,
            $this->isTrue(),
            "Column {$colName} with type {$type} not found in table {$tableName}"
        );
    }
}
