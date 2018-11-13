<?php

declare(strict_types=1);

namespace marvin255\fias\service\db;

use marvin255\fias\mapper\SqlMapperInterface;

/**
 * Интерфейс для объекта, который реализует взаимодействие с базой данных.
 */
interface DbInterface
{
    /**
     * Добавляет новую строку в базу данных.
     *
     * @param \marvin255\fias\mapper\SqlMapperInterface $mapper
     * @param array                                     $item
     *
     * @return void
     *
     * @throws \marvin255\fias\service\db\Exception
     */
    public function insert(SqlMapperInterface $mapper, array $item);

    /**
     * Обновляет строку в базе данных.
     *
     * @param \marvin255\fias\mapper\SqlMapperInterface $mapper
     * @param array                                     $item
     *
     * @return void
     *
     * @throws \marvin255\fias\service\db\Exception
     */
    public function update(SqlMapperInterface $mapper, array $item);

    /**
     * Удаляет строку из базы данных.
     *
     * @param \marvin255\fias\mapper\SqlMapperInterface $mapper
     * @param array                                     $item
     *
     * @return void
     *
     * @throws \marvin255\fias\service\db\Exception
     */
    public function delete(SqlMapperInterface $mapper, array $item);

    /**
     * Создает таблицу в базе данных, согласно описанию из маппера.
     *
     * @param \marvin255\fias\mapper\SqlMapperInterface $mapper
     *
     * @return void
     *
     * @throws \marvin255\fias\service\db\Exception
     */
    public function createTable(SqlMapperInterface $mapper);

    /**
     * Удалает таблицу в базе данных, согласно описанию из маппера.
     *
     * @param \marvin255\fias\mapper\SqlMapperInterface $mapper
     *
     * @return void
     *
     * @throws \marvin255\fias\service\db\Exception
     */
    public function dropTable(SqlMapperInterface $mapper);

    /**
     * Очищает от содержимого таблицу в базе данных, согласно описанию из маппера.
     *
     * @param \marvin255\fias\mapper\SqlMapperInterface $mapper
     *
     * @return void
     *
     * @throws \marvin255\fias\service\db\Exception
     */
    public function truncateTable(SqlMapperInterface $mapper);

    /**
     * Указывает, что данный потребитель закончил работу с базой данных и нужно
     * дописать все оставшиеся запросы и сбросить все временные данные.
     *
     * @return void
     */
    public function complete();
}