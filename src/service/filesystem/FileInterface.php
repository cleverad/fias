<?php

declare(strict_types=1);

namespace marvin255\fias\service\filesystem;

/**
 * Интерфейс для объекта, который инкапсулирует обращение к файлу в локальной
 * файловой системе.
 */
interface FileInterface
{
    /**
     * Возвращает путь и имя файла.
     *
     * @return string
     */
    public function getPath(): string;

    /**
     * Возвращает путь без имени файла.
     *
     * @return string
     */
    public function getDirname(): string;

    /**
     * Возвращает имя файла (без расширения).
     *
     * @return string
     */
    public function getFilename(): string;

    /**
     * Возвращает расширение файла.
     *
     * @return string
     */
    public function getExtension(): string;

    /**
     * Возвращает полное имя файла (с расширением).
     *
     * @return string
     */
    public function getBasename(): string;

    /**
     * Возвращает true, если файл существует в файловой системе.
     *
     * @return bool
     */
    public function isExists(): bool;

    /**
     * Удаляет файл из файловой системы.
     */
    public function delete();
}
