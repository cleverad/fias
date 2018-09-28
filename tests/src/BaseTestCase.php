<?php

declare(strict_types=1);

namespace marvin255\fias\tests;

use PHPUnit\Framework\TestCase;
use Faker;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;

/**
 * Базовый класс для всех тестов, все тесты в библиотеке должны наследоваться
 * от него.
 *
 * Реализует дополнительный функционал, например, обращение к faker.
 */
abstract class BaseTestCase extends TestCase
{
    /**
     * @var \Faker\Generator|null
     */
    private $faker;
    /**
     * @var string
     */
    private $tempDir;

    /**
     * @return \Faker\Generator
     */
    public function faker(): Faker\Generator
    {
        if ($this->faker === null) {
            $this->faker = Faker\Factory::create();
        }

        return $this->faker;
    }

    /**
     * Возвращает путь до базовой папки для тестов.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected function getTempDir(): string
    {
        if ($this->tempDir === null) {
            $this->tempDir = sys_get_temp_dir();
            if (!$this->tempDir || !is_writable($this->tempDir)) {
                throw new RuntimeException(
                    "Can't find or write temporary folder: {$this->tempDir}"
                );
            }
            $this->tempDir .= DIRECTORY_SEPARATOR . 'bxcodegen';
            if (!mkdir($this->tempDir, 0777, true)) {
                throw new RuntimeException(
                    "Can't create temporary folder: {$this->tempDir}"
                );
            }
        }

        return $this->tempDir;
    }

    /**
     * Создает тестовую директорию во временной папке и возвращает путь до нее.
     *
     * @param string $name
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected function getPathToTestDir(string $name = ''): string
    {
        if ($name === '') {
            $name = preg_replace('/[^a-zA-Z0-9_]+/', '_', get_class($this));
            $name = strtolower(trim($name, " \t\n\r\0\x0B_"));
        }

        $pathToFolder = $this->getTempDir() . DIRECTORY_SEPARATOR . $name;
        if (!mkdir($pathToFolder, 0777, true)) {
            throw new RuntimeException("Can't mkdir {$pathToFolder} folder");
        }

        return $pathToFolder;
    }

    /**
     * Создает тестовый файл во временной директории.
     *
     * @param string $name
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected function getPathToTestFile(string $name = ''): string
    {
        if ($name === '') {
            $name = preg_replace('/[^a-zA-Z0-9_]+/', '_', get_class($this));
            $name = strtolower(trim($name, " \t\n\r\0\x0B_")) . '.txt';
        }

        $pathToFile = $this->getTempDir() . DIRECTORY_SEPARATOR . $name;
        if (file_put_contents($pathToFile, $this->faker()->unique()->word) === false) {
            throw new RuntimeException("Can't create file {$pathToFile}");
        }

        return $pathToFile;
    }

    /**
     * Удаляет содержимое папки.
     *
     * @param string $folderPath
     */
    protected function removeDir(string $folderPath)
    {
        if (is_dir($folderPath)) {
            $it = new RecursiveDirectoryIterator(
                $folderPath,
                RecursiveDirectoryIterator::SKIP_DOTS
            );
            $files = new RecursiveIteratorIterator(
                $it,
                RecursiveIteratorIterator::CHILD_FIRST
            );
            foreach ($files as $file) {
                if ($file->isDir()) {
                    rmdir($file->getRealPath());
                } elseif ($file->isFile()) {
                    unlink($file->getRealPath());
                }
            }
            rmdir($folderPath);
        }
    }

    /**
     * Удаляет тестовую директорию и все ее содержимое.
     */
    public function tearDown()
    {
        if ($this->tempDir) {
            $this->removeDir($this->tempDir);
        }

        return parent::tearDown();
    }
}
