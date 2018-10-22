<?php

declare(strict_types=1);

namespace marvin255\fias\tests\service\filesystem;

use marvin255\fias\tests\BaseTestCase;
use marvin255\fias\service\filesystem\Directory;
use InvalidArgumentException;
use RuntimeException;

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
    protected $pathToDir = '';

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
        $dir = new Directory($this->pathToDir);

        $this->assertSame($this->pathToDir, $dir->getPath());
    }

    /**
     * Возвращает ли объект путь до каталога, в котором расположен открытый каталог.
     */
    public function testGetDirnamer()
    {
        $dir = new Directory($this->pathToDir);

        $this->assertSame(dirname($this->pathToDir), $dir->getDirname());
    }

    /**
     * Возвращает ли объект имя каталога без пути.
     */
    public function testGetBaseName()
    {
        $dir = new Directory($this->pathToDir);

        $this->assertSame(
            pathinfo($this->pathToDir, PATHINFO_BASENAME),
            $dir->getBasename()
        );
    }

    /**
     * Проверяет как объект определяет существует ли заданная папка.
     */
    public function testIsExists()
    {
        $dir = new Directory($this->pathToDir);
        $unexistedDir = new Directory(__DIR__ . '/unexisted');

        $this->assertTrue($dir->isExists());
        $this->assertFalse($unexistedDir->isExists());
    }

    /**
     * Проверяет, что объект может создать каталог, для которого инициирован,
     * если каталога не существует.
     */
    public function testCreate()
    {
        $pathToDir = $this->pathToDir . '/' . $this->faker()->unique()->word
            . '/' . $this->faker()->unique()->word;

        $dir = new Directory($pathToDir);

        $this->assertFalse(is_dir($pathToDir));
        $dir->create();
        $this->assertTrue(is_dir($pathToDir));
    }

    /**
     * Проверяет, что объект выбросит исключение, если не сможет создать
     * каталог, для которого инициирован.
     */
    public function testCreateException()
    {
        $dir = new Directory($this->pathToDir);

        $this->expectException(RuntimeException::class);
        $dir->create();
    }

    /**
     * Проверяет, что объект удалит каталог, для которого инициирован,
     * вместе со всеми его вложенными файлами и каталогами.
     */
    public function testDelete()
    {
        $dirName = pathinfo($this->pathToDir, PATHINFO_BASENAME);
        $testDir = $this->getPathToTestDir($dirName . '/' . $this->faker()->unique()->word);
        $testFile = $this->getPathToTestFile($dirName . '/' . $this->faker()->unique()->word);

        $dir = new Directory($this->pathToDir);

        $this->assertTrue(is_dir($this->pathToDir));
        $this->assertTrue(is_dir($testDir));
        $this->assertTrue(file_exists($testFile));
        $dir->delete();
        $this->assertFalse(is_dir($this->pathToDir));
        $this->assertFalse(is_dir($testDir));
        $this->assertFalse(file_exists($testFile));
    }

    /**
     * Проверяет, что объект удалит все вложенные каталоги и файлы для своего каталога,
     * но каталог оставит нетронутым.
     */
    public function testEmpty()
    {
        $dirName = pathinfo($this->pathToDir, PATHINFO_BASENAME);
        $testDir = $this->getPathToTestDir($dirName . '/' . $this->faker()->unique()->word);
        $testFile = $this->getPathToTestFile($dirName . '/' . $this->faker()->unique()->word);

        $dir = new Directory($this->pathToDir);

        $this->assertTrue(is_dir($this->pathToDir));
        $this->assertTrue(is_dir($testDir));
        $this->assertTrue(file_exists($testFile));
        $dir->empty();
        $this->assertTrue(is_dir($this->pathToDir));
        $this->assertFalse(is_dir($testDir));
        $this->assertFalse(file_exists($testFile));
    }

    /**
     * Проверяет, что объект выбросит исключение при попытке создать каталог
     * уровнем выше своего.
     */
    public function testWrongChildDirName()
    {
        $dir = new Directory($this->pathToDir);

        $this->expectException(InvalidArgumentException::class);
        $dir->createChildDirectory('test/../test');
    }

    /**
     * Проверяет, что объект выбросит исключение при попытке создать файл
     * уровнем выше своего.
     */
    public function testWrongChildFileName()
    {
        $dir = new Directory($this->pathToDir);

        $this->expectException(InvalidArgumentException::class);
        $dir->createChildFile('../test.txt');
    }

    /**
     * Проверяет, что объект ищет вложенные файлы по указанному паттерну.
     */
    public function testFindFilesByPattern()
    {
        $dirName = pathinfo($this->pathToDir, PATHINFO_BASENAME);
        $file1 = $this->getPathToTestFile("{$dirName}/1__find_me__1.txt");
        $file2 = $this->getPathToTestFile("{$dirName}/2__find_me__2.txt");
        $file3 = $this->getPathToTestFile("{$dirName}/cantfindme.txt");
        $etalonFiles = [$file1, $file2];

        $dir = new Directory($this->pathToDir);
        $testFiles = array_map(function ($file) {
            return $file->getPath();
        }, $dir->findFilesByPattern('*_find_me_*.txt'));

        sort($etalonFiles);
        sort($testFiles);

        $this->assertSame($etalonFiles, $testFiles);
    }

    /**
     * Проверяет, что объект работает как итератор.
     */
    public function testIterator()
    {
        $dirName = pathinfo($this->pathToDir, PATHINFO_BASENAME);
        $children = [
            $this->getPathToTestDir($dirName . '/.' . $this->faker()->unique()->word),
            $this->getPathToTestDir($dirName . '/' . $this->faker()->unique()->word),
            $this->getPathToTestFile($dirName . '/.' . $this->faker()->unique()->word),
            $this->getPathToTestFile($dirName . '/' . $this->faker()->unique()->word),
        ];

        $dir = new Directory($this->pathToDir);

        $tested = [];
        foreach ($dir as $key => $child) {
            $tested[] = $child->getPath();
        }

        sort($tested);
        sort($children);

        $this->assertSame($children, $tested);
    }

    /**
     * Задает путь к каталогу для тестов.
     */
    public function setUp()
    {
        $this->pathToDir = $this->getPathToTestDir();

        return parent::setUp();
    }
}
