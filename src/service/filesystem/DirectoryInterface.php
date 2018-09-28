<?php

declare(strict_types=1);

namespace marvin255\fias\service\filesystem;

/**
 * Интерфейс для объекта, который инкапсулирует обращение к папке в локальной
 * файловой системе.
 */
interface DirectoryInterface
{
    /**
     * Возвращает путь и имя папки.
     *
     * @return string
     */
    public function getPath(): string;

    /**
     * Возвращает путь без имени папки.
     *
     * @return string
     */
    public function getDirname(): string;

    /**
     * Возвращает имя папки.
     *
     * @return string
     */
    public function getBasename(): string;

    /**
     * Возвращает true, если папка существует в файловой системе.
     *
     * @return bool
     */
    public function isExists(): bool;
}
