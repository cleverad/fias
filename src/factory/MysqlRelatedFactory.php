<?php

declare(strict_types=1);

namespace marvin255\fias\factory;

use marvin255\fias\Pipe;
use marvin255\fias\task\Cleanup;
use marvin255\fias\task\DownloadFull;
use marvin255\fias\task\Unpack;
use marvin255\fias\task\CreateStructure;
use marvin255\fias\task\MysqlLoadDataInsert;

/**
 * Фабричный объект, который создает пайпы для соответствующих типов задач,
 * используя классы оптимизированные исключительно для mysql.
 */
class MysqlRelatedFactory extends InternalServicesFactory
{
    /**
     * {@inheritdoc}
     *
     * @throws Exception
     */
    public function createInstallPipe(): Pipe
    {
        $informer = $this->createInformer();
        $downloader = $this->createDownloader();
        $workDir = $this->createWorkDir();
        $unpacker = $this->createUnpacker();
        $reader = $this->createReader();
        $db = $this->createDb();
        $pdo = $this->createPdo();
        $log = $this->createLog();
        $mappers = $this->getMappers();

        $pipe = new Pipe;

        if ($this->config->getBool('create_structure', false)) {
            foreach ($mappers as $mapper) {
                $pipe->pipe(new CreateStructure($db, $mapper, $log));
            }
        }

        $pipe->pipe(new DownloadFull($informer, $downloader, $workDir, $log));
        $pipe->pipe(new Unpack($unpacker, $workDir, $log));

        foreach ($mappers as $mapper) {
            $pipe->pipe(new MysqlLoadDataInsert($reader, $pdo, $mapper, $log));
        }

        $pipe->setCleanup(new Cleanup($log));

        return $pipe;
    }
}
