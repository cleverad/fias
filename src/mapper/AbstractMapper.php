<?php

declare(strict_types=1);

namespace marvin255\fias\mapper;

/**
 * Базовый класс для универсального маппера.
 */
abstract class AbstractMapper implements SqlMapperInterface, XmlMapperInterface
{
    use XmlMapperTrait;

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
