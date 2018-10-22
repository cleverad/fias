<?php

declare(strict_types=1);

namespace marvin255\fias\mapper;

/**
 * Интерфейс для маппера, который поясняет как правильно извлечь данные
 * из xml файла.
 */
interface XmlMapperInterface extends MapperInterface
{
    /**
     * Возвращает псевдо xpath путь к сущности внутри xml.
     *
     * @return string
     */
    public function getXmlPath(): string;

    /**
     * Получает на вход строку с xml для сущности и извлекает из нее ассоциативный
     * массив с данными сущности.
     *
     * @param string $xml
     *
     * @return array
     */
    public function extractArrayFromXml(string $xml): array;

    /**
     * Возвращает маску имени файла, в котором хранятся данные для вставки.
     *
     * @return string
     */
    public function getInsertFileMask(): string;

    /**
     * Возвращает маску имени файла, в котором хранятся данные для удаления.
     *
     * @return string
     */
    public function getDeleteFileMask(): string;
}
