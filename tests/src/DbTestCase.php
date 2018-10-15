<?php

declare(strict_types=1);

namespace marvin255\fias\tests;

use PHPUnit\DbUnit\Database\Connection;
use PHPUnit\DbUnit\TestCaseTrait;
use PDO;

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
}
