<?php

declare(strict_types=1);

namespace marvin255\fias\task;

use marvin255\fias\state\StateInterface;
use marvin255\fias\service\fias\InformerInterface;
use marvin255\fias\service\fias\InformerResultInterface;
use marvin255\fias\service\downloader\DownloaderInterface;
use marvin255\fias\service\filesystem\DirectoryInterface;
use marvin255\fias\service\filesystem\FileInterface;
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
        $informerResult = $this->fetchInfoFromService($state);

        if ($informerResult->hasResult()) {
            $file = $this->downloadFile($informerResult->getUrl());
            $state->setParameter('informerResult', $informerResult);
            $state->setParameter('archive', $file);
        } else {
            $this->info('Empty response');
            $state->complete();
        }
    }

    /**
     * Получает ссылку на файл с полной базой данных из сервиса ФИАС.
     *
     * @throws Exception
     */
    protected function fetchInfoFromService(StateInterface $state): InformerResultInterface
    {
        $this->info('Fetching archive url from fias information service');

        return $this->informer->getCompleteInfo();
    }

    /**
     * Загружает файл по ссылке.
     *
     * @throws Exception
     */
    protected function downloadFile(string $url): FileInterface
    {
        $this->info("Url fetched: {$url}");

        $file = $this->workDir->createChildFile('archive.rar');

        try {
            $this->info("Downloading file from {$url} to " . $file->getPath());
            $this->downloader->download($url, $file);
            $this->info('Downloading complete ' . $file->getPath());
        } catch (Exception $e) {
            $this->info('Downloading break, removing ' . $file->getPath());
            $file->delete();
            throw $e;
        }

        return $file;
    }
}
