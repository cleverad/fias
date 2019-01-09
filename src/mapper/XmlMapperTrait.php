<?php

declare(strict_types=1);

namespace marvin255\fias\mapper;

use SimpleXMLElement;
use Throwable;
use RuntimeException;

/**
 * Трэйт с хэлперами для объекта, который поясняет как извлечь данные ФИАС из
 * XML в требуемом формате.
 */
trait XmlMapperTrait
{
    use MapperTrait;

    /**
     * Псевдо xpath путь к сущности внутри xml.
     *
     * @var string
     */
    protected $xmlPath = '';
    /**
     * Маска имени файла, в котором хранятся данные для вставки.
     *
     * @var string
     */
    protected $insertFileMask = '';
    /**
     * Маска имени файла, в котором хранятся данные для удаления.
     *
     * @var string
     */
    protected $deleteFileMask = '';

    /**
     * Возвращает псевдо xpath путь к сущности внутри xml.
     *
     * @return string
     */
    public function getXmlPath(): string
    {
        return $this->xmlPath;
    }

    /**
     * Получает на вход строку с xml для сущности и извлекает из нее ассоциативный
     * массив с данными сущности.
     *
     * @param string $xml
     *
     * @return array
     */
    public function extractArrayFromXml(string $xml): array
    {
        $return = [];

        try {
            $attributes = $this->convertStringToSimpleXml($xml)->attributes();
            foreach ($this->getMap() as $fieldName => $field) {
                $value = strval($attributes[$fieldName] ?? '');
                $return[$fieldName] = $field->convertToData($value);
            }
        } catch (Throwable $e) {
            throw new RuntimeException($e->getMessage(), (int) $e->getCode(), $e);
        }

        return $return;
    }

    /**
     * Возвращает маску имени файла, в котором хранятся данные для вставки.
     *
     * @return string
     */
    public function getInsertFileMask(): string
    {
        return $this->insertFileMask;
    }

    /**
     * Возвращает маску имени файла, в котором хранятся данные для удаления.
     *
     * @return string
     */
    public function getDeleteFileMask(): string
    {
        return $this->deleteFileMask;
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
        libxml_use_internal_errors(true);

        $return = simplexml_load_string($xml);

        $xmlErrors = $this->extractLibXmlErrors();
        if (!($return instanceof SimpleXMLElement) || !empty($xmlErrors)) {
            throw new RuntimeException(implode(', ', $xmlErrors));
        }

        libxml_use_internal_errors(false);

        return $return;
    }

    /**
     * Возвращает ошибки, которые произошли во время работы libxml.
     *
     * @return string[]
     */
    protected function extractLibXmlErrors(): array
    {
        $errors = [];

        foreach (libxml_get_errors() as $error) {
        }

        libxml_clear_errors();

        return $errors;
    }
}
