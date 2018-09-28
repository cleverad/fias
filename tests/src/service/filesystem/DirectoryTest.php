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
     * Путь к тестовому каталогу.
     *
     * @var string
     */
    protected $dirName = '';

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
        $dir = new Directory($this->dirName);

        $this->assertSame($this->dirName, $dir->getPath());
    }

    /**
     * Возвращает ли объект путь до каталога, в котором располежен открытый каталог.
     */
    public function testGetDirName()
    {
        $dir = new Directory($this->dirName);

        $this->assertSame(dirname($this->dirName), $dir->getDirname());
    }

    /**
     * Возвращает ли объект имя каталога без пути.
     */
    public function testGetBaseName()
    {
        $dir = new Directory($this->dirName);

        $this->assertSame(
            pathinfo($this->dirName, PATHINFO_BASENAME),
            $dir->getBasename()
        );
    }

    /**
     * Проверяет как объект определяет существует ли заданная папка.
     */
    public function testIsExists()
    {
        $dir = new Directory($this->dirName);
        $unexistedDir = new Directory(__DIR__ . '/unexisted');

        $this->assertTrue($dir->isExists());
        $this->assertFalse($unexistedDir->isExists());
    }

    /**
     * Задает путь к каталогу для тестов.
     */
    public function setUp()
    {
        $this->dirName = $this->getPathToTestDir();

        return parent::setUp();
    }
}
