<?php

declare(strict_types=1);

namespace marvin255\fias\mapper;

/**
 * Интерфейс для объекта, который поясняет как правильно записать данные в
 * базу данных.
 */
interface SqlMapperInterface extends MapperInterface
{
    /**
     * Возвращает имя таблицы, в которой нужно будет сохранить данные.
     *
     * @return string
     */
    public function getSqlName(): string;

    /**
     * Возвращает массив с названиями полей, которые должны быть использованы
     * в качестве первичного ключа.
     *
     * @return string[]
     */
    public function getSqlPrimary(): array;

    /**
     * Убирает из входящего массива все поля, ключей для которых нет в списке
     * полей первичного ключа.
     *
     * @param array $messyArray
     *
     * @return array
     */
    public function mapPrimaries(array $messyArray): array;

    /**
     * Убирает из входящего массива все поля, ключи для которых есть в списке
     * полей первичного ключа.
     *
     * @param array $messyArray
     *
     * @return array
     */
    public function mapNotPrimaries(array $messyArray): array;

    /**
     * Возвращает массив с массивами, в которых содержатся имена полей сущности
     * для формирования дополнитедльных индексов в базе данных.
     *
     * @return string[][]
     */
    public function getSqlIndexes(): array;

    /**
     * Возвращает число разделов, на которые нужно разбить данные при хранении
     * в базе данных.
     *
     * @return int
     */
    public function getSqlPartitionsCount(): int;

    /**
     * Возвращает название поле, которое следует использовать для разделения
     * таблицы на части.
     *
     * @return string
     */
    public function getSqlPartitionField(): string;
}
