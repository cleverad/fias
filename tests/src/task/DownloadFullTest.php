<?php

declare(strict_types=1);

namespace marvin255\fias\tests\task;

use marvin255\fias\tests\BaseTestCase;
use marvin255\fias\state\StateInterface;
use marvin255\fias\service\fias\InformerInterface;
use marvin255\fias\service\fias\InformerResultInterface;
use marvin255\fias\service\downloader\DownloaderInterface;
use marvin255\fias\service\filesystem\FileInterface;
use marvin255\fias\service\filesystem\DirectoryInterface;
use marvin255\fias\task\DownloadFull;
use Psr\Log\LoggerInterface;
use RuntimeException;

/**
 * Тест для объекта, который загружает архив с полной версией ФИАС.
 */
class DownloadFullTest extends BaseTestCase
{
    /**
     * Проверяет, что объект вызывает все соответствующие методы и передавет
     * в сосотояние все требуемые данные.
     */
    public function testRun()
    {
        $url = $this->faker()->unique()->url;
        $version = $this->faker()->unique()->randomDigit + 1;
        $path = '/' . $this->faker()->unique()->word . '/' . $this->faker()->unique()->word;

        $informerResult = $this->getMockBuilder(InformerResultInterface::class)->getMock();
        $informerResult->method('getUrl')->will($this->returnValue($url));
        $informerResult->method('getVersion')->will($this->returnValue($version));
        $informerResult->method('hasResult')->will($this->returnValue(true));

        $informer = $this->getMockBuilder(InformerInterface::class)->getMock();
        $informer->method('getCompleteInfo')->will($this->returnValue($informerResult));

        $file = $this->getMockBuilder(FileInterface::class)->getMock();
        $file->method('getPath')->will($this->returnValue($path));
        $workDir = $this->getMockBuilder(DirectoryInterface::class)->getMock();
        $workDir->method('createChildFile')->will($this->returnValue($file));

        $downloader = $this->getMockBuilder(DownloaderInterface::class)->getMock();
        $downloader->expects($this->once())->method('download')->with(
            $this->equalTo($url),
            $this->equalTo($file)
        );

        $logger = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $logger->expects($this->atLeastOnce())->method('info');

        $state = $this->getMockBuilder(StateInterface::class)->getMock();
        $state->expects($this->at(0))->method('setParameter')->with(
            $this->equalTo('informerResult'),
            $this->equalTo($informerResult)
        );
        $state->expects($this->at(1))->method('setParameter')->with(
            $this->equalTo('archive'),
            $this->equalTo($file)
        );

        $task = new DownloadFull($informer, $downloader, $workDir, $logger);
        $task->run($state);
    }

    /**
     * Проверяет, что в случае, если ссылка не получена, задача прервет цепочку
     * выполнения.
     */
    public function testRunEmptyResponse()
    {
        $informerResult = $this->getMockBuilder(InformerResultInterface::class)->getMock();
        $informerResult->method('getUrl')->will($this->returnValue(''));
        $informerResult->method('getVersion')->will($this->returnValue(0));
        $informerResult->method('hasResult')->will($this->returnValue(false));

        $informer = $this->getMockBuilder(InformerInterface::class)->getMock();
        $informer->method('getCompleteInfo')->will($this->returnValue($informerResult));

        $workDir = $this->getMockBuilder(DirectoryInterface::class)->getMock();
        $downloader = $this->getMockBuilder(DownloaderInterface::class)->getMock();

        $state = $this->getMockBuilder(StateInterface::class)->getMock();
        $state->expects($this->once())->method('complete');

        $task = new DownloadFull($informer, $downloader, $workDir);
        $task->run($state);
    }

    /**
     * Проверяет, что в случае, если при загрузке произойдет ошибка,
     * недозагруженный файл будет удален.
     */
    public function testRunDownloadingException()
    {
        $url = $this->faker()->unique()->url;
        $version = $this->faker()->unique()->randomDigit + 1;
        $path = '/' . $this->faker()->unique()->word . '/' . $this->faker()->unique()->word;

        $informerResult = $this->getMockBuilder(InformerResultInterface::class)->getMock();
        $informerResult->method('getUrl')->will($this->returnValue($url));
        $informerResult->method('getVersion')->will($this->returnValue($version));
        $informerResult->method('hasResult')->will($this->returnValue(true));

        $informer = $this->getMockBuilder(InformerInterface::class)->getMock();
        $informer->method('getCompleteInfo')->will($this->returnValue($informerResult));

        $file = $this->getMockBuilder(FileInterface::class)->getMock();
        $file->method('getPath')->will($this->returnValue($path));
        $file->expects($this->once())->method('delete');

        $workDir = $this->getMockBuilder(DirectoryInterface::class)->getMock();
        $workDir->method('createChildFile')->will($this->returnValue($file));

        $downloader = $this->getMockBuilder(DownloaderInterface::class)->getMock();
        $downloader->method('download')->will($this->throwException(new RuntimeException));

        $state = $this->getMockBuilder(StateInterface::class)->getMock();

        $task = new DownloadFull($informer, $downloader, $workDir);

        $this->expectException(RuntimeException::class);
        $task->run($state);
    }
}
