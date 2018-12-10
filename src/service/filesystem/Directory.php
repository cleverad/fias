<?php

declare(strict_types=1);

namespace marvin255\fias\service\filesystem;

use Iterator;
use CallbackFilterIterator;
use DirectoryIterator;
use InvalidArgumentException;
use RuntimeException;

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
     * Внутренний итератор для обхода вложенных файлов и каталогов.
     *
     * @var \Iterator|null
     */
    private $iterator;

    /**
     * @param string $path
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $path)
    {
        $trimmed = trim($path);

        if ($trimmed === '') {
            throw new InvalidArgumentException("path parameter can't be empty");
        }

        if (!preg_match('/^(\/|[a-zA-Z]{1}:\\\).+$/', $trimmed)) {
            throw new InvalidArgumentException("Path {$path} must starts from root");
        }

        $this->path = $trimmed;
        $this->info = [
            'dirname' => dirname($trimmed),
            'basename' => pathinfo($trimmed, PATHINFO_BASENAME),
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
    public function getDirname(): string
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

    /**
     * {@inheritdoc}
     *
     * @throws RuntimeException
     */
    public function create(): DirectoryInterface
    {
        if (!@mkdir($this->getPath(), 0777, true)) {
            throw new RuntimeException(
                "Can't create directory " . $this->getPath()
            );
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @throws RuntimeException
     */
    public function delete(): DirectoryInterface
    {
        foreach ($this as $child) {
            $child->delete();
        }
        if (!@rmdir($this->getPath())) {
            throw new RuntimeException(
                "Can't delete directory: " . $this->getPath()
            );
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @throws RuntimeException
     */
    public function empty(): DirectoryInterface
    {
        foreach ($this as $child) {
            $child->delete();
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @throws InvalidArgumentException
     */
    public function createChildDirectory(string $name): DirectoryInterface
    {
        if (preg_match('#(^|\\\|/)\.{1,}(\\\|/|$)#i', $name)) {
            throw new InvalidArgumentException("Wrong folder name {$name}");
        }

        return new self($this->path . '/' . $name);
    }

    /**
     * {@inheritdoc}
     *
     * @throws InvalidArgumentException
     */
    public function createChildFile(string $name): FileInterface
    {
        if (preg_match('#(^|\\\|/)\.{1,}(\\\|/|$)#i', $name)) {
            throw new InvalidArgumentException("Wrong file name {$name}");
        }

        return new File($this->path . '/' . $name);
    }

    /**
     * @inheritdoc
     */
    public function findFilesByPattern(string $pattern): array
    {
        $return = [];
        $regexp = '/^' . implode('[^\/\.]+', array_map('preg_quote', explode('*', $pattern))) . '$/';
        foreach ($this->getIterator() as $file) {
            if ($file->isFile() && preg_match($regexp, $file->getFilename())) {
                $return[] = $this->createChildFile($file->getFilename());
            }
        }

        return $return;
    }

    /**
     * Реализация итератора.
     *
     * @return DirectoryInterface|FileInterface
     */
    public function current()
    {
        $item = $this->getIterator()->current();

        if ($item->isDir()) {
            $return = $this->createChildDirectory($item->getFilename());
        } else {
            $return = $this->createChildFile($item->getFilename());
        }

        return $return;
    }

    /**
     * Реализация итератора.
     *
     * @return string
     */
    public function key()
    {
        return $this->getIterator()->key();
    }

    /**
     * Реализация итератора.
     */
    public function next()
    {
        $this->getIterator()->next();
    }

    /**
     * Реализация итератора.
     */
    public function rewind()
    {
        $this->getIterator()->rewind();
    }

    /**
     * Реализация итератора.
     *
     * @return bool
     */
    public function valid()
    {
        return $this->getIterator()->valid();
    }

    /**
     * Возвращает внутренний объект итератора для перебора содержимого данной папки.
     *
     * @return Iterator
     *
     * @throws UnexpectedValueException
     */
    protected function getIterator(): Iterator
    {
        if ($this->iterator === null) {
            $dirIterator = new DirectoryIterator($this->getPath());
            $filter = function (string $current, string $key, DirectoryIterator $iterator): bool {
                return !$iterator->isDot();
            };
            $this->iterator = new CallbackFilterIterator($dirIterator, $filter);
        }

        return $this->iterator;
    }
}
