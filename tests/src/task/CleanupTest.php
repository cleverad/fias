<?php

declare(strict_types=1);

namespace marvin255\fias\tests\task;

use marvin255\fias\tests\BaseTestCase;
use marvin255\fias\state\StateInterface;
use marvin255\fias\service\filesystem\FileInterface;
use marvin255\fias\service\filesystem\DirectoryInterface;
use marvin255\fias\task\Cleanup;

/**
 * Тест для объекта, который распаковывает архив с ФИАС в указанную папку.
 */
class CleanupTest extends BaseTestCase
{
    /**
     * Проверяет, что объект удаляет все временные данные.
     */
    public function testRun()
    {
        $archive = $this->getMockBuilder(FileInterface::class)->getMock();
        $archive->method('isExists')->will($this->returnValue(true));
        $archive->expects($this->once())->method('delete');

        $extracted = $this->getMockBuilder(DirectoryInterface::class)->getMock();
        $extracted->method('isExists')->will($this->returnValue(true));
        $extracted->expects($this->once())->method('delete');

        $state = $this->getMockBuilder(StateInterface::class)->getMock();
        $state->method('getParameter')->will($this->returnCallback(function ($name) use ($archive, $extracted) {
            $params = ['archive' => $archive, 'extracted' => $extracted];

            return isset($params[$name]) ? $params[$name] : null;
        }));

        $task = new Cleanup;
        $task->run($state);
    }
}
