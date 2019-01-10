<?php

declare(strict_types=1);

namespace marvin255\fias\service\db;

/**
 * Объект, который создает запросы для sqlite.
 */
class SqliteQueryRunner extends MysqlQueryRunner
{
    public function beginInsert()
    {
    }

    public function completeInsert()
    {
    }

    protected function makeMetaForCreateTable(string $partitionField, int $partitionCount): string
    {
        return '';
    }
}
