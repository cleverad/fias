<?php

declare(strict_types=1);

namespace marvin255\fias\service\downloader;

use marvin255\fias\service\filesystem\FileInterface;

/**
 * Интерфейс для объекта, который скачивает файл по ссылке.
 */
interface DownloaderInterface
{
    /**
     * Скачивает файл по ссылке из первого параметра в локальный файл,
     * указанный во втором параметре.
     *
     * @param string        $urlToDownload
     * @param FileInterface $localFile
     *
     * @return void
     *
     * @throws RuntimeException
     */
    public function download(string $urlToDownload, FileInterface $localFile);
}
