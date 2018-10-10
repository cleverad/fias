<?php

declare(strict_types=1);

namespace marvin255\fias\mapper;

use SimpleXMLElement;
use Throwable;
use RuntimeException;

/**
 * Базовый класс для универсального маппера.
 */
abstract class AbstractMapper implements SqlMapperInterface, XmlMapperInterface
{
    /**
     * Флаг, который указывает, что поля были закешированы.
     *
     * @var bool
     */
    protected $isMapCached = false;
    /**
     * Закешированный массив сущностей.
     *
     * @var \marvin255\fias\mapper\FieldInterface[]
     */
    protected $cachedMap = [];

    /**
     * Создает список полей и возвращает ассоциативный массив, в котором ключами
     * служат названия полей, а значениями - объекты FieldInterface.
     *
     * @return \marvin255\fias\mapper\FieldInterface[]
     */
    abstract protected function createFields(): array;

    /**
     * @inhertitdoc
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
     * {@inhertitdoc}.
     *
     * @throws \RuntimeException
     */
    public function extractArrayFromXml(string $xml): array
    {
        $return = [];
        $fields = $this->getMap();

        try {
            $simpleXml = $this->convertStringToSimpleXml($xml);
            $attributes = $simpleXml->attributes();
            foreach ($fields as $fieldName => $field) {
                $value = isset($attributes[$fieldName])
                    ? (string) $attributes[$fieldName]
                    : null;
                $return[$fieldName] = $field->convert($value);
            }
        } catch (Throwable $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

        return $return;
    }

    /**
     * @inhertitdoc
     */
    public function getSqlName(): string
    {
        return trim(str_replace('\\', '_', strtolower(get_class($this))), '_');
    }

    /**
     * @inhertitdoc
     */
    public function getSqlIndexes(): array
    {
        return [];
    }

    /**
     * @inhertitdoc
     */
    public function getSqlPartitionsCount(): int
    {
        return 1;
    }

    /**
     * @inhertitdoc
     */
    public function getSqlPartitionsFields(): array
    {
        return [];
    }

    /**
     * Преобразует строку xml в объект SimpleXml.
     *
     * @param string $xml
     *
     * @return \SimpleXMLElement
     *
     * @throws \RuntimeException
     */
    protected function convertStringToSimpleXml(string $xml): SimpleXMLElement
    {
        try {
            $return = simplexml_load_string($xml);
        } catch (Throwable $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

        return $return;
    }
}
