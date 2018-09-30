<?php

declare(strict_types=1);

namespace marvin255\fias\service\filesystem;

use Iterator;
use CallbackFilterIterator;
use DirectoryIterator;
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
     * Внутренний итератор для обхода вложенных файлов и каталогов.
     *
     * @var \Iterator|null
     */
    private $iterator;

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
     * @throws \InvalidArgumentException
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
     * @throws \InvalidArgumentException
     */
    public function createChildFile(string $name): FileInterface
    {
        if (preg_match('#(^|\\\|/)\.{1,}(\\\|/|$)#i', $name)) {
            throw new InvalidArgumentException("Wrong file name {$name}");
        }

        return new File($this->path . '/' . $name);
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
     * @return \Iterator
     *
     * @throws \UnexpectedValueException
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
