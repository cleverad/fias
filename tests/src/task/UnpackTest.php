<?php

declare(strict_types=1);

namespace marvin255\fias\tests\state;

use marvin255\fias\tests\BaseTestCase;
use marvin255\fias\state\StateInterface;
use marvin255\fias\service\unpacker\UnpackerInterface;
use marvin255\fias\service\filesystem\FileInterface;
use marvin255\fias\service\filesystem\DirectoryInterface;
use marvin255\fias\task\Unpack;
use Psr\Log\LoggerInterface;
use InvalidArgumentException;

/**
 * Тест для объекта, который распаковывает архив с ФИАС в указанную папку.
 */
class UnpackTest extends BaseTestCase
{
    /**
     * Проверяет, что объект вызывает все соответствующие методы и передавет
     * в сосотояние все требуемые данные.
     */
    public function testRun()
    {
        $dir = '/' . $this->faker()->unique()->word;
        $path = $dir . '/' . $this->faker()->unique()->word;

        $archive = $this->getMockBuilder(FileInterface::class)->getMock();
        $archive->method('getPath')->will($this->returnValue($path));

        $extractDir = $this->getMockBuilder(DirectoryInterface::class)->getMock();
        $extractDir->method('getPath')->will($this->returnValue($dir));

        $workDir = $this->getMockBuilder(DirectoryInterface::class)->getMock();
        $workDir->method('createChildDirectory')->will($this->returnValue($extractDir));

        $unpacker = $this->getMockBuilder(UnpackerInterface::class)->getMock();
        $unpacker->expects($this->once())->method('unpack')->with(
            $this->equalTo($archive),
            $this->equalTo($extractDir)
        );

        $logger = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $logger->expects($this->atLeastOnce())->method('info');

        $state = $this->getMockBuilder(StateInterface::class)->getMock();
        $state->method('getParameter')->with($this->equalTo('archive'))->will($this->returnValue($archive));
        $state->expects($this->once())->method('setParameter')->with(
            $this->equalTo('extracted'),
            $this->equalTo($extractDir)
        );

        $task = new Unpack($unpacker, $workDir, $logger);
        $task->run($state);
    }

    /**
     * Проверяет, что объект выбросит исключение, если не найдет путь к архиву.
     */
    public function testRunNoArchiveException()
    {
        $workDir = $this->getMockBuilder(DirectoryInterface::class)->getMock();
        $unpacker = $this->getMockBuilder(UnpackerInterface::class)->getMock();
        $state = $this->getMockBuilder(StateInterface::class)->getMock();

        $task = new Unpack($unpacker, $workDir);

        $this->expectException(InvalidArgumentException::class);
        $task->run($state);
    }
}
