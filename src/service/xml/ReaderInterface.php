<?php

declare(strict_types=1);

namespace marvin255\fias\service\xml;

use marvin255\fias\mapper\XmlMapperInterface;
use Iterator;

/**
 * Интерфейс для объекта, который читает данные из xml файла.
 */
interface ReaderInterface extends Iterator
{
    /**
     * Задает объект-маппер, который описывает как извлечь целевой объект из
     * xml.
     *
     * @param \marvin255\fias\mapper\XmlMapperInterface $mapper
     *
     * @return \marvin255\fias\mapper\XmlMapperInterface
     */
    public function setMapper(XmlMapperInterface $mapper): ReaderInterface;

    /**
     * Открывает файл на чтение, пытается найти путь указанный в маппере, если
     * путь найден, то открывает файли возвращает правду, если не найден, то
     * возвращает ложь.
     *
     * @param string $path
     *
     * @return bool
     */
    public function openFile(string $path): bool;

    /**
     * Закрывает открытый файл, если такой был.
     *
     * @return void
     */
    public function closeFile();
}
