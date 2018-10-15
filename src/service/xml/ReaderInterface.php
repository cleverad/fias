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
     * @return $this
     */
    public function setMapper(XmlMapperInterface $mapper): self;

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
     * Закрывает открытый файл, если такой был. Возврщает правду, если
     * файл был закрыт, и ложь, если файл не был закрыт.
     *
     * @return bool
     */
    public function closeFile(): bool;
}
