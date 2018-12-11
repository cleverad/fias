<?php

declare(strict_types=1);

namespace marvin255\fias\task;

use marvin255\fias\state\StateInterface;
use marvin255\fias\service\unpacker\UnpackerInterface;
use marvin255\fias\service\filesystem\FileInterface;
use marvin255\fias\service\filesystem\DirectoryInterface;
use Psr\Log\LoggerInterface;
use InvalidArgumentException;

/**
 * Задача для распаковки архива с ФИАС.
 */
class Unpack extends AbstractTask
{
    /**
     * @var UnpackerInterface
     */
    protected $unpacker;
    /**
     * @var DirectoryInterface
     */
    protected $workDir;

    /**
     * @param UnpackerInterface  $unpacker
     * @param DirectoryInterface $workDir
     * @param LoggerInterface    $logger
     */
    public function __construct(UnpackerInterface $unpacker, DirectoryInterface $workDir, LoggerInterface $logger = null)
    {
        $this->unpacker = $unpacker;
        $this->workDir = $workDir;
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    public function run(StateInterface $state)
    {
        $archive = $state->getParameter('archive');
        if (!($archive instanceof FileInterface)) {
            throw new InvalidArgumentException(
                'There is no archive file in state object'
            );
        }

        $extractDir = $this->workDir->createChildDirectory('extract');
        if (!$extractDir->isExists()) {
            $extractDir->create();
        }

        $this->info(
            'Unpacking archive ' . $archive->getPath()
            . ' to folder ' . $extractDir->getPath()
        );
        $this->unpacker->unpack($archive, $extractDir);
        $state->setParameter('extracted', $extractDir);
        $this->info('Unpacking complete');
    }
}
