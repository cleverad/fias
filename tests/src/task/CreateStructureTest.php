<?php

declare(strict_types=1);

namespace marvin255\fias\tests\task;

use marvin255\fias\tests\BaseTestCase;
use marvin255\fias\state\StateInterface;
use marvin255\fias\service\db\DbInterface;
use marvin255\fias\mapper\AbstractMapper;
use marvin255\fias\task\CreateStructure;
use Psr\Log\LoggerInterface;

/**
 * Тест для объекта, который создает структуру в бд для указанного маппера.
 */
class CreateStructureTest extends BaseTestCase
{
    /**
     * Проверяет, что объект вызывает все соответствующие методы и передавет
     * в сосотояние все требуемые данные.
     */
    public function testRun()
    {
        $mapper = $this->getMockBuilder(AbstractMapper::class)->getMock();
        $mapper->method('getSqlName')->will($this->returnValue('test'));

        $db = $this->getMockBuilder(DbInterface::class)->getMock();
        $db->expects($this->once($mapper))->method('dropTable')->with($this->equalTo($mapper));
        $db->expects($this->once($mapper))->method('createTable')->with($this->equalTo($mapper));

        $logger = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $logger->expects($this->atLeastOnce())->method('info');

        $state = $this->getMockBuilder(StateInterface::class)->getMock();

        $task = new CreateStructure($db, $mapper, $logger);
        $task->run($state);
    }
}
