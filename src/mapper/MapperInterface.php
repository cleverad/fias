<?php

declare(strict_types=1);

namespace marvin255\fias\mapper;

use marvin255\fias\mapper\field\FieldInterface;

/**
 * Интерфейс для объекта, который возвращает список полей для сущности ФИАС.
 * Служит преждевсего для получения результатов из xml и записи их в базу данных.
 */
interface MapperInterface
{
    /**
     * Возвращает список полей данной сущности.
     *
     * @return FieldInterface[]
     */
    public function getMap(): array;

    /**
     * Убирает из входящего массива все поля, ключей для которых нет в списке
     * полей для данного маппера.
     *
     * @param array $messyArray
     *
     * @return array
     */
    public function mapArray(array $messyArray): array;

    /**
     * Приводит значения к строковым представлениям.
     *
     * @param array $messyArray
     *
     * @return array
     */
    public function convertToStrings(array $messyArray): array;
}
