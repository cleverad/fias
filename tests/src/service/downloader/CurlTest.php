<?php

declare(strict_types=1);

namespace marvin255\fias\tests\service\downloader;

use marvin255\fias\tests\BaseTestCase;
use marvin255\fias\service\downloader\Curl;
use marvin255\fias\service\filesystem\FileInterface;
use RuntimeException;

/**
 * Тест для объекта, который загружает файл по ссылке с помощью curl.
 */
class CurlTest extends BaseTestCase
{
    /**
     * Проверяет, что объект загружает файл.
     */
    public function testDownload()
    {
        $source = $this->faker()->unique()->url;

        $destinationPath = $this->getPathToTestFile('archive.rar');
        $destination = $this->getMockBuilder(FileInterface::class)->getMock();
        $destination->method('getPath')->will($this->returnValue($destinationPath));

        $curl = $this->getMockBuilder(Curl::class)
            ->setMethods(['curlDownload'])
            ->disableOriginalConstructor()
            ->getMock();
        $curl->expects($this->once())->method('curlDownload')
            ->with($this->callback(function ($requestOptions) use ($source) {
                return in_array($source, $requestOptions)
                    && isset($requestOptions[CURLOPT_FILE])
                    && is_resource($requestOptions[CURLOPT_FILE]);
            }))
            ->will($this->returnValue([true, 200, null]));

        $curl->download($source, $destination);
    }

    /**
     * Проверяет, что объект выбрасывает исключение, если произошла ошибка
     * во время загрузки файла.
     */
    public function testDownloadCurlErrorException()
    {
        $source = $this->faker()->unique()->url;

        $destinationPath = $this->getPathToTestFile('archive.rar');
        $destination = $this->getMockBuilder(FileInterface::class)->getMock();
        $destination->method('getPath')->will($this->returnValue($destinationPath));

        $curl = $this->getMockBuilder(Curl::class)
            ->setMethods(['curlDownload'])
            ->disableOriginalConstructor()
            ->getMock();
        $curl->expects($this->once())->method('curlDownload')
            ->will($this->returnValue([false, 0, 'error']));

        $this->expectException(RuntimeException::class, 'error');
        $curl->download($source, $destination);
    }

    /**
     * Проверяет, что объект выбрасывает исключение, если в ответ по ссылке возвращается
     * любой статус кроме 200.
     */
    public function testDownloadWrongResponseCodeException()
    {
        $source = $this->faker()->unique()->url;

        $destinationPath = $this->getPathToTestFile('archive.rar');
        $destination = $this->getMockBuilder(FileInterface::class)->getMock();
        $destination->method('getPath')->will($this->returnValue($destinationPath));

        $curl = $this->getMockBuilder(Curl::class)
            ->setMethods(['curlDownload'])
            ->disableOriginalConstructor()
            ->getMock();
        $curl->expects($this->once())->method('curlDownload')
            ->will($this->returnValue([true, 413, null]));

        $this->expectException(RuntimeException::class, '413');
        $curl->download($source, $destination);
    }

    /**
     * Проверяет, что объект выбрасывает исключение, если не удалось открыть
     * целевой файл для записи в локальную файловую систему.
     */
    public function testDownloadCantOpenFileException()
    {
        $source = $this->faker()->unique()->url;

        $destinationPath = '/wrong/path/to/file.rar';
        $destination = $this->getMockBuilder(FileInterface::class)->getMock();
        $destination->method('getPath')->will($this->returnValue($destinationPath));

        $curl = $this->getMockBuilder(Curl::class)
            ->setMethods(['curlDownload'])
            ->disableOriginalConstructor()
            ->getMock();
        $curl->expects($this->never())->method('curlDownload');

        $this->expectException(RuntimeException::class, $destinationPath);
        $curl->download($source, $destination);
    }
}
