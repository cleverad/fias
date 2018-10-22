<?php

declare(strict_types=1);

namespace marvin255\fias\task;

use marvin255\fias\state\StateInterface;
use marvin255\fias\service\filesystem\FileInterface;
use marvin255\fias\service\filesystem\DirectoryInterface;
use Psr\Log\LoggerInterface;

/**
 * Задача, которая удаляет все временные данные, созданные остальными задачами.
 */
class Cleanup extends AbstractTask
{
    /**
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    public function run(StateInterface $state)
    {
        $this->info('Cleaning up');

        $archive = $state->getParameter('archive');
        if ($archive instanceof FileInterface && $archive->isExists()) {
            $this->info('Cleaning up archive file');
            $archive->delete();
        }

        $extractedDir = $state->getParameter('extracted');
        if ($extractedDir instanceof DirectoryInterface && $extractedDir->isExists()) {
            $this->info('Cleaning up extracted directory');
            $extractedDir->delete();
        }

        $this->info('Cleaning up complete');
    }
}
