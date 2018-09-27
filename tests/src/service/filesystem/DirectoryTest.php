<?php

declare(strict_types=1);

namespace marvin255\fias\tests\service\filesystem;

use marvin255\fias\tests\BaseTestCase;
use marvin255\fias\service\filesystem\Directory;
use InvalidArgumentException;

/**
 * Тест для объекта, который инкапсулирует доступ к каталогу на жестком диске.
 */
class DirectoryTest extends BaseTestCase
{
    /**
     * Будет ли выброшено исключение для пустого пути в конструкторе.
     */
    public function testEmptyPathInConstructException()
    {
        $this->expectException(InvalidArgumentException::class);
        $dir = new Directory('');
    }

    /**
     * Будет ли выброшено исключение, если задать в конструкторе относительный путь.
     */
    public function testRelativePathInConstructException()
    {
        $this->expectException(InvalidArgumentException::class);
        $dir = new Directory('some/folder');
    }

    /**
     * Возвращает ли объект полный путь до текущего открытого каталога.
     */
    public function testGetPath()
    {
        $dirName = __DIR__ . '/_fixtures/dir';
        $dir = new Directory($dirName);

        $this->assertSame($dirName, $dir->getPath());
    }

    /**
     * Возвращает ли объект путь до каталога, в котором располежен открытый каталог.
     */
    public function testGetDirName()
    {
        $dirName = __DIR__ . '/_fixtures/dir';
        $dir = new Directory($dirName);

        $this->assertSame(dirname($dirName), $dir->getDirName());
    }

    /**
     * Возвращает ли объект имя каталога без пути.
     */
    public function testGetBaseName()
    {
        $dirName = __DIR__ . '/_fixtures/dir';
        $dir = new Directory($dirName);

        $this->assertSame('dir', $dir->getBaseName());
    }

    /**
     * Проверяет как объект определяет существует ли заданная папка.
     */
    public function testIsExists()
    {
        $dir = new Directory(__DIR__ . '/_fixtures/dir');
        $unexistedDir = new Directory(__DIR__ . '/_fixtures/unexisted');

        $this->assertTrue($dir->isExists());
        $this->assertFalse($unexistedDir->isExists());
    }
}
