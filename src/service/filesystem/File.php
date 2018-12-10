<?php

declare(strict_types=1);

namespace marvin255\fias\service\filesystem;

use InvalidArgumentException;
use RuntimeException;

/**
 * Объект, который инкапсулирует обращение к файлу в локальной
 * файловой системе.
 */
class File implements FileInterface
{
    /**
     * Абсолютный путь к файлу.
     *
     * @var string
     */
    protected $path = '';
    /**
     * Данные о файле, возвращаемые pathinfo.
     *
     * @var string[]
     */
    protected $info = [];

    /**
     * Конструктор. Задает абсолютный путь к файлу.
     *
     * Папка должна существовать и должна быть доступна на запись.
     *
     * @param string $path
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $path)
    {
        $trimmed = trim($path);

        if (empty($trimmed)) {
            throw new InvalidArgumentException("path parameter can't be empty");
        }

        if (!is_dir(dirname($trimmed))) {
            throw new InvalidArgumentException(
                "Directory for file {$path} must exist"
            );
        }

        $this->path = $trimmed;
        $this->info = array_map('trim', pathinfo($trimmed));
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
    public function getDirname(): string
    {
        return $this->info['dirname'];
    }

    /**
     * @inheritdoc
     */
    public function getFilename(): string
    {
        return $this->info['filename'];
    }

    /**
     * @inheritdoc
     */
    public function getExtension(): string
    {
        return $this->info['extension'];
    }

    /**
     * @inheritdoc
     */
    public function getBasename(): string
    {
        return $this->info['basename'];
    }

    /**
     * @inheritdoc
     */
    public function isExists(): bool
    {
        return file_exists($this->path);
    }

    /**
     * {@inheritdoc}
     *
     * @throws RuntimeException
     */
    public function delete()
    {
        if (!@unlink($this->path)) {
            throw new RuntimeException(
                "Can't unlink file " . $this->getPath()
            );
        }
    }
}
