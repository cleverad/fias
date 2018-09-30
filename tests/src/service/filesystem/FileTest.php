<?php

declare(strict_types=1);

namespace marvin255\fias\tests\service\filesystem;

use marvin255\fias\tests\BaseTestCase;
use marvin255\fias\service\filesystem\File;
use InvalidArgumentException;

/**
 * Тест для объекта, который инкапсулирует доступ к файлу на жестком диске.
 */
class FileTest extends BaseTestCase
{
    /**
     * Путь к тестовому файлу.
     *
     * @var string
     */
    protected $fileName = '';

    /**
     * Будет ли выброшено исключение для пустого пути в конструкторе.
     */
    public function testEmptyPathInConstructException()
    {
        $this->expectException(InvalidArgumentException::class);
        $dir = new File('');
    }

    /**
     * Будет ли выброшено исключение, если задать в конструкторе не существующий путь.
     */
    public function testUnexistedPathInConstructException()
    {
        $this->expectException(InvalidArgumentException::class);
        $dir = new File(__DIR__ . '/unexisted/file.txt');
    }

    /**
     * Возвращает ли объект путь к файлу и его имя.
     */
    public function testGetPath()
    {
        $file = new File($this->fileName);

        $this->assertSame($this->fileName, $file->getPath());
    }

    /**
     * Возвращает ли объект путь к файлу без имени.
     */
    public function testGetDirname()
    {
        $file = new File($this->fileName);

        $this->assertSame(dirname($this->fileName), $file->getDirname());
    }

    /**
     * Возвращает ли объект имя файла без пути.
     */
    public function testGetFilename()
    {
        $file = new File($this->fileName);

        $this->assertSame(
            pathinfo($this->fileName, PATHINFO_FILENAME),
            $file->getFilename()
        );
    }

    /**
     * Возвращает ли объект расширение файла.
     */
    public function testGetExtension()
    {
        $file = new File($this->fileName);

        $this->assertSame(
            pathinfo($this->fileName, PATHINFO_EXTENSION),
            $file->getExtension()
        );
    }

    /**
     * Возвращает ли объект имя файла без расширения.
     */
    public function testGetBasename()
    {
        $file = new File($this->fileName);

        $this->assertSame(
            pathinfo($this->fileName, PATHINFO_BASENAME),
            $file->getBasename()
        );
    }

    /**
     * Проверяет ли объект существование файла.
     */
    public function testIsExists()
    {
        $file = new File($this->fileName);
        $unexistedFile = new File(__DIR__ . '/unexisted.file');

        $this->assertTrue($file->isExists());
        $this->assertFalse($unexistedFile->isExists());
    }

    /**
     * Удаляет ли объект файл, к которому привязан.
     */
    public function testDelete()
    {
        $file = new File($this->fileName);

        $this->assertFileExists($this->fileName);
        $this->assertTrue($file->delete());
        $this->assertFileNotExists($this->fileName);
    }

    /**
     * Задает путь к файлу для тестов.
     */
    public function setUp()
    {
        $this->fileName = $this->getPathToTestFile();

        return parent::setUp();
    }
}
