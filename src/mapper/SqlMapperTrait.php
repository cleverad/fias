<?php

declare(strict_types=1);

namespace marvin255\fias\mapper;

/**
 * Трэйт для объекта, который поясняет как правильно записать данные в
 * базу данных.
 */
trait SqlMapperTrait
{
    use MapperTrait;

    /**
     * Имя таблицы в БД для дянной сущности.
     *
     * Если не задано, то по умолчанию преобразует имя класса маппера.
     *
     * @var string|null
     */
    protected $sqlName;
    /**
     * Массив с названиями полей, которые должны быть использованы
     * в качестве первичного ключа.
     *
     * @var string[]|string
     */
    protected $sqlPrimary = [];
    /**
     * Массив с массивами, в которых содержатся имена полей сущности
     * для формирования дополнитедльных индексов в базе данных.
     *
     * @var string[][]
     */
    protected $sqlIndexes = [];
    /**
     * Число разделов, на которые нужно разбить данные при хранении
     * в базе данных.
     *
     * @var int
     */
    protected $sqlPartitionsCount = 1;
    /**
     * Название поля, которое следует использовать для разделения
     * таблицы на части.
     *
     * @var string
     */
    protected $sqlPartitionField = '';

    /**
     * Возвращает имя таблицы, в которой нужно будет сохранить данные.
     *
     * @return string
     */
    public function getSqlName(): string
    {
        $name = $this->sqlName;
        if ($name === null) {
            $name = trim(str_replace('\\', '_', strtolower(get_class($this))), '_');
        }

        return $name;
    }

    /**
     * Возвращает массив с названиями полей, которые должны быть использованы
     * в качестве первичного ключа.
     *
     * @return string[]
     */
    public function getSqlPrimary(): array
    {
        return is_array($this->sqlPrimary) ? $this->sqlPrimary : [$this->sqlPrimary];
    }

    /**
     * Убирает из входящего массива все поля, ключей для которых нет в списке
     * полей первичного ключа.
     *
     * @param array $messyArray
     *
     * @return array
     */
    public function mapPrimaries(array $messyArray): array
    {
        $primaries = $this->getSqlPrimary();
        $primariesArray = [];

        foreach ($primaries as $primary) {
            $primariesArray[$primary] = $messyArray[$primary] ?? null;
        }

        return $primariesArray;
    }

    /**
     * Убирает из входящего массива все поля, ключи для которых есть в списке
     * полей первичного ключа.
     *
     * @param array $messyArray
     *
     * @return array
     */
    public function mapNotPrimaries(array $messyArray): array
    {
        $map = $this->getMap();
        $primaries = $this->getSqlPrimary();
        $notPrimariesArray = [];

        foreach ($messyArray as $fieldName => $value) {
            if (isset($map[$fieldName]) && !in_array($fieldName, $primaries)) {
                $notPrimariesArray[$fieldName] = $value;
            }
        }

        return $notPrimariesArray;
    }

    /**
     * Возвращает массив с массивами, в которых содержатся имена полей сущности
     * для формирования дополнитедльных индексов в базе данных.
     *
     * @return string[][]
     */
    public function getSqlIndexes(): array
    {
        return $this->sqlIndexes;
    }

    /**
     * Возвращает число разделов, на которые нужно разбить данные при хранении
     * в базе данных.
     *
     * @return int
     */
    public function getSqlPartitionsCount(): int
    {
        return $this->sqlPartitionsCount;
    }

    /**
     * Возвращает название поля, которое следует использовать для разделения
     * таблицы на части.
     *
     * @return string
     */
    public function getSqlPartitionField(): string
    {
        return $this->sqlPartitionField;
    }
}
