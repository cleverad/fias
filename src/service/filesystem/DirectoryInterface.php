<?php

declare(strict_types=1);

namespace marvin255\fias\service\filesystem;

use Iterator;

/**
 * Интерфейс для объекта, который инкапсулирует обращение к каталогу в локальной
 * файловой системе.
 */
interface DirectoryInterface extends Iterator
{
    /**
     * Возвращает путь и имя катлаога.
     *
     * @return string
     */
    public function getPath(): string;

    /**
     * Возвращает путь без имени каталога.
     *
     * @return string
     */
    public function getDirname(): string;

    /**
     * Возвращает имя каталога.
     *
     * @return string
     */
    public function getBasename(): string;

    /**
     * Возвращает true, если каталог существует в файловой системе.
     *
     * @return bool
     */
    public function isExists(): bool;

    /**
     * Создает каталог и все родительские каталоги, если потребуется.
     *
     * @return $this
     */
    public function create(): DirectoryInterface;

    /**
     * Удаляет каталог из файловой системы.
     *
     * @return $this
     */
    public function delete(): DirectoryInterface;

    /**
     * Удаляет все содержимое каталога, сам каталог остается нетронутым.
     *
     * @return $this
     */
    public function empty(): DirectoryInterface;

    /**
     * Создает вложенный каталог.
     *
     * @param string $name
     *
     * @return DirectoryInterface
     */
    public function createChildDirectory(string $name): DirectoryInterface;

    /**
     * Создает вложенный файл.
     *
     * @param string $name
     *
     * @return FileInterface
     */
    public function createChildFile(string $name): FileInterface;

    /**
     * Ищет файл в текущей папке по указанному паттерну.
     *
     * @param string $pattern
     *
     * @return FileInterface[]
     */
    public function findFilesByPattern(string $pattern): array;
}
