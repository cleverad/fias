<?php

declare(strict_types=1);

namespace marvin255\fias\mapper;

/**
 * Интерфейс для объекта, который возвращает список полей для сущности ФИАС.
 * Служит преждевсего для получения результатов из xml и записи их в базу данных.
 */
interface MapperInterface
{
    /**
     * Возвращает список полей данной сущности.
     *
     * @return \marvin255\fias\mapper\FieldInterface[]
     */
    public function getMap(): array;
}
