<?php

declare(strict_types=1);

namespace marvin255\fias\service\filesystem;

use InvalidArgumentException;

/**
 * Объект, который инкапсулирует обращение к каталогу в локальной файловой системе.
 */
class Directory implements DirectoryInterface
{
    /**
     * Абсолютный путь к каталогу.
     *
     * @var string
     */
    protected $path = '';
    /**
     * Информация о каталоге.
     *
     * @var string[]
     */
    protected $info = [];

    /**
     * @param string $path
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(string $path)
    {
        if (trim($path, ' \t\n\r\0\x0B\\/') === '') {
            throw new InvalidArgumentException("path parameter can't be empty");
        }

        if (!preg_match('/^(\/|[a-zA-Z]{1}:\\\).+$/', $path)) {
            throw new InvalidArgumentException('path must starts from root');
        }

        $this->path = $path;
        $this->info = [
            'dirname' => dirname($path),
            'basename' => pathinfo($this->path, PATHINFO_BASENAME),
        ];
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
        return $this->info['dirname'];
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
        return is_dir($this->path);
    }
}
