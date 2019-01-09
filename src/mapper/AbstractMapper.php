<?php

declare(strict_types=1);

namespace marvin255\fias\mapper;

use marvin255\fias\mapper\field\FieldInterface;

/**
 * Базовый класс для универсального маппера.
 */
abstract class AbstractMapper implements SqlMapperInterface, XmlMapperInterface
{
    use XmlMapperTrait;

    /**
     * Флаг, который указывает, что поля были закешированы.
     *
     * @var bool
     */
    protected $isMapCached = false;
    /**
     * Закешированный массив сущностей.
     *
     * @var FieldInterface[]
     */
    protected $cachedMap = [];

    /**
     * Создает список полей и возвращает ассоциативный массив, в котором ключами
     * служат названия полей, а значениями - объекты FieldInterface.
     *
     * @return FieldInterface[]
     */
    abstract protected function createFields(): array;

    /**
     * @inheritdoc
     */
    public function getMap(): array
    {
        if (!$this->isMapCached) {
            $this->isMapCached = true;
            $this->cachedMap = $this->createFields();
        }

        return $this->cachedMap;
    }

    /**
     * @inheritdoc
     */
    public function mapArray(array $messyArray): array
    {
        $map = $this->getMap();
        $mappedArray = [];

        foreach ($map as $fieldName => $field) {
            $mappedArray[$fieldName] = $messyArray[$fieldName] ?? null;
        }

        return $mappedArray;
    }

    /**
     * @inheritdoc
     */
    public function convertToStrings(array $messyArray): array
    {
        $map = $this->getMap();
        $convertedArray = [];

        foreach ($messyArray as $fieldName => $value) {
            $convertedArray[$fieldName] = isset($map[$fieldName])
                ? $map[$fieldName]->convertToString($value)
                : $value;
        }

        return $convertedArray;
    }

    /**
     * @inheritdoc
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
     * @inheritdoc
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
     * @inheritdoc
     */
    public function getSqlName(): string
    {
        return trim(str_replace('\\', '_', strtolower(get_class($this))), '_');
    }

    /**
     * @inheritdoc
     */
    public function getSqlIndexes(): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getSqlPartitionsCount(): int
    {
        return 1;
    }

    /**
     * @inheritdoc
     */
    public function getSqlPartitionField(): string
    {
        return '';
    }
}
