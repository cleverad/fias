<?php

declare(strict_types=1);

namespace marvin255\fias\task;

use marvin255\fias\state\StateInterface;
use marvin255\fias\service\fias\InformerInterface;
use marvin255\fias\service\downloader\DownloaderInterface;
use marvin255\fias\service\filesystem\DirectoryInterface;
use Psr\Log\LoggerInterface;
use Exception;

/**
 * Задача для загрузки архива с полной версией ФИАС.
 */
class DownloadFull extends AbstractTask
{
    /**
     * @var InformerInterface
     */
    protected $informer;
    /**
     * @var DownloaderInterface
     */
    protected $downloader;
    /**
     * @var DirectoryInterface
     */
    protected $workDir;

    /**
     * @param InformerInterface   $informer
     * @param DownloaderInterface $downloader
     * @param DirectoryInterface  $workDir
     * @param LoggerInterface     $logger
     */
    public function __construct(InformerInterface $informer, DownloaderInterface $downloader, DirectoryInterface $workDir, LoggerInterface $logger = null)
    {
        $this->informer = $informer;
        $this->downloader = $downloader;
        $this->workDir = $workDir;
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    public function run(StateInterface $state)
    {
        $this->info('Fetching archive url from fias information service');
        $informerResult = $this->informer->getCompleteInfo();

        if ($informerResult->hasResult()) {
            $this->info('Url fetched: ' . $informerResult->getUrl());

            $file = $this->workDir->createChildFile('archive.rar');

            try {
                $this->info('Downloading file from ' . $informerResult->getUrl() . ' to ' . $file->getPath());
                $this->downloader->download($informerResult->getUrl(), $file);
                $this->info('Downloading complete ' . $file->getPath());
            } catch (Exception $e) {
                $this->info('Downloading break, removing ' . $file->getPath());
                $file->delete();
                throw $e;
            }

            $state->setParameter('informerResult', $informerResult);
            $state->setParameter('archive', $file);
        } else {
            $this->info('Empty response');
            $state->complete();
        }
    }
}
