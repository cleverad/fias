<?php

declare(strict_types=1);

namespace marvin255\fias\tests\service\unpacker;

use marvin255\fias\tests\BaseTestCase;
use marvin255\fias\service\unpacker\Rar;
use marvin255\fias\service\filesystem\FileInterface;
use marvin255\fias\service\filesystem\DirectoryInterface;
use RarArchive;
use RarEntry;
use RuntimeException;

/**
 * Тест для объекта, который распаковывает файлы из rar архива.
 */
class RarTest extends BaseTestCase
{
    /**
     * Проверяет, что объект выбросит исключение, если файла с архивом не
     * существует.
     */
    public function testUnpackUnexistedSourceException()
    {
        $sourcePath = '/unexited/archive.rar';
        $source = $this->getMockBuilder(FileInterface::class)->getMock();
        $source->method('getPath')->will($this->returnValue($sourcePath));
        $source->method('isExists')->will($this->returnValue(false));

        $destinationPath = $this->getPathToTestDir();
        $destination = $this->getMockBuilder(DirectoryInterface::class)->getMock();
        $destination->method('getPath')->will($this->returnValue($destinationPath));
        $destination->method('isExists')->will($this->returnValue(true));

        $unpacker = new Rar;

        $this->expectException(RuntimeException::class, $sourcePath);
        $unpacker->unpack($source, $destination);
    }

    /**
     * Проверяет, что объект выбросит исключение, если папки, в которую должен
     * быть распакован архив, не существует.
     */
    public function testUnpackUnexistedDestinationException()
    {
        $sourcePath = $this->getPathToTestFile('archive.rar');
        $source = $this->getMockBuilder(FileInterface::class)->getMock();
        $source->method('getPath')->will($this->returnValue($sourcePath));
        $source->method('isExists')->will($this->returnValue(true));

        $destinationPath = '/unexisted/destination';
        $destination = $this->getMockBuilder(DirectoryInterface::class)->getMock();
        $destination->method('getPath')->will($this->returnValue($destinationPath));
        $destination->method('isExists')->will($this->returnValue(false));

        $unpacker = new Rar;

        $this->expectException(RuntimeException::class, $destinationPath);
        $unpacker->unpack($source, $destination);
    }

    /**
     * Проверяет распаковку архива.
     */
    public function testUnpack()
    {
        $sourcePath = $this->getPathToTestFile('archive.rar');
        $source = $this->getMockBuilder(FileInterface::class)->getMock();
        $source->method('getPath')->will($this->returnValue($sourcePath));
        $source->method('isExists')->will($this->returnValue(true));

        $destinationPath = $this->getPathToTestDir();
        $destination = $this->getMockBuilder(DirectoryInterface::class)->getMock();
        $destination->method('getPath')->will($this->returnValue($destinationPath));
        $destination->method('isExists')->will($this->returnValue(true));

        $rarEntry1 = $this->getMockBuilder(RarEntry::class)
            ->setMethods(['extract'])
            ->getMock();
        $rarEntry1->expects($this->once())->method('extract')
            ->with($this->equalTo($destinationPath))
            ->will($this->returnValue(true));
        $rarEntry2 = $this->getMockBuilder(RarEntry::class)
            ->setMethods(['extract'])
            ->getMock();
        $rarEntry2->expects($this->once())->method('extract')
            ->with($this->equalTo($destinationPath))
            ->will($this->returnValue(true));

        $rarArchive = $this->getMockBuilder(RarArchive::class)
            ->setMethods(['close', 'getEntries'])
            ->getMock();
        $rarArchive->expects($this->once())->method('close');
        $rarArchive->expects($this->once())->method('getEntries')
            ->will($this->returnValue([$rarEntry1, $rarEntry2]));

        $unpacker = $this->getMockBuilder(Rar::class)
            ->setMethods(['getRarInstance'])
            ->disableOriginalConstructor()
            ->getMock();
        $unpacker->expects($this->once())->method('getRarInstance')
            ->with($this->callback(function ($sourceArg) use ($sourcePath) {
                return $sourceArg->getPath() === $sourcePath;
            }))
            ->will($this->returnValue($rarArchive));

        $unpacker->unpack($source, $destination);
    }

    /**
     * Проверяет, что объект выбросит исключение, если не сможет получить
     * список файлов в архиве.
     */
    public function testUnpackGetEntriesException()
    {
        $sourcePath = $this->getPathToTestFile('archive.rar');
        $source = $this->getMockBuilder(FileInterface::class)->getMock();
        $source->method('getPath')->will($this->returnValue($sourcePath));
        $source->method('isExists')->will($this->returnValue(true));

        $destinationPath = $this->getPathToTestDir();
        $destination = $this->getMockBuilder(DirectoryInterface::class)->getMock();
        $destination->method('getPath')->will($this->returnValue($destinationPath));
        $destination->method('isExists')->will($this->returnValue(true));

        $rarArchive = $this->getMockBuilder(RarArchive::class)
            ->setMethods(['close', 'getEntries'])
            ->getMock();
        $rarArchive->expects($this->once())->method('close');
        $rarArchive->expects($this->once())->method('getEntries')
            ->will($this->returnValue(false));

        $unpacker = $this->getMockBuilder(Rar::class)
            ->setMethods(['getRarInstance'])
            ->disableOriginalConstructor()
            ->getMock();
        $unpacker->expects($this->once())->method('getRarInstance')
            ->will($this->returnValue($rarArchive));

        $this->expectException(RuntimeException::class);
        $unpacker->unpack($source, $destination);
    }

    /**
     * Проверяет, что объект выбросит исключение, если не удастя извлечь
     * какой-лтбо файл из архива.
     */
    public function testUnpackExtractEntryException()
    {
        $sourcePath = $this->getPathToTestFile('archive.rar');
        $source = $this->getMockBuilder(FileInterface::class)->getMock();
        $source->method('getPath')->will($this->returnValue($sourcePath));
        $source->method('isExists')->will($this->returnValue(true));

        $destinationPath = $this->getPathToTestDir();
        $destination = $this->getMockBuilder(DirectoryInterface::class)->getMock();
        $destination->method('getPath')->will($this->returnValue($destinationPath));
        $destination->method('isExists')->will($this->returnValue(true));

        $rarEntry1 = $this->getMockBuilder(RarEntry::class)
            ->setMethods(['extract'])
            ->getMock();
        $rarEntry1->expects($this->once())->method('extract')
            ->with($this->equalTo($destinationPath))
            ->will($this->returnValue(true));
        $rarEntry2 = $this->getMockBuilder(RarEntry::class)
            ->setMethods(['extract'])
            ->getMock();
        $rarEntry2->expects($this->once())->method('extract')
            ->with($this->equalTo($destinationPath))
            ->will($this->returnValue(false));

        $rarArchive = $this->getMockBuilder(RarArchive::class)
            ->setMethods(['close', 'getEntries'])
            ->getMock();
        $rarArchive->expects($this->once())->method('close');
        $rarArchive->expects($this->once())->method('getEntries')
            ->will($this->returnValue([$rarEntry1, $rarEntry2]));

        $unpacker = $this->getMockBuilder(Rar::class)
            ->setMethods(['getRarInstance'])
            ->disableOriginalConstructor()
            ->getMock();
        $unpacker->expects($this->once())->method('getRarInstance')
            ->will($this->returnValue($rarArchive));

        $this->expectException(RuntimeException::class);
        $unpacker->unpack($source, $destination);
    }
}
