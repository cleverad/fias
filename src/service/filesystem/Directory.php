<?php

declare(strict_types=1);

namespace marvin255\fias\service\filesystem;

use InvalidArgumentException;

/**
 * Объект, который инкапсулирует обращение к папке в локальной файловой системе.
 */
class Directory implements DirectoryInterface
{
    /**
     * Абсолютный путь к папке.
     *
     * @var string
     */
    protected $path = '';

    /**
     * Конструктор. Задает абсолютный путь к папке.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        if (trim($path, ' \t\n\r\0\x0B\\/') === '') {
            throw new InvalidArgumentException(
                "absolutePath parameter can't be empty"
            );
        }

        if (!preg_match('/^\/[a-z_]+.*[^\/]+$/', $path)) {
            throw new InvalidArgumentException(
                'absolutePath must starts from root, and consist of digits and letters'
            );
        }

        $this->path = $path;
    }

    /**
     * @inheritdoc
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @inheritdoc
     */
    public function getDirName(): string
    {
        return dirname($this->path);
    }

    /**
     * @inheritdoc
     */
    public function getBaseName(): string
    {
        return pathinfo($this->path, PATHINFO_BASENAME);
    }

    /**
     * @inheritdoc
     */
    public function isExists(): bool
    {
        return (bool) is_dir($this->path);
    }
}
