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
    protected $absolutePath = '';

    /**
     * Конструктор. Задает абсолютный путь к папке.
     *
     * @param string $absolutePath
     */
    public function __construct(string $absolutePath)
    {
        if (trim($absolutePath, ' \t\n\r\0\x0B\\/') === '') {
            throw new InvalidArgumentException(
                "absolutePath parameter can't be empty"
            );
        }

        if (!preg_match('/^\/[a-z_]+.*[^\/]+$/', $absolutePath)) {
            throw new InvalidArgumentException(
                'absolutePath must starts from root, and consist of digits and letters'
            );
        }

        $this->absolutePath = $absolutePath;
    }

    /**
     * @inheritdoc
     */
    public function getPath(): string
    {
        return $this->absolutePath;
    }

    /**
     * @inheritdoc
     */
    public function getDirName(): string
    {
        return dirname($this->absolutePath);
    }

    /**
     * @inheritdoc
     */
    public function getBaseName(): string
    {
        return pathinfo($this->absolutePath, PATHINFO_BASENAME);
    }

    /**
     * @inheritdoc
     */
    public function isExists(): bool
    {
        return (bool) is_dir($this->absolutePath);
    }
}
