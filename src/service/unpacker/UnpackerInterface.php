<?php

declare(strict_types=1);

namespace marvin255\fias\service\unpacker;

use marvin255\fias\service\filesystem\DirectoryInterface;
use marvin255\fias\service\filesystem\FileInterface;

/**
 * Интерфейс для объекта, который распаковывает данные из архива.
 */
interface UnpackerInterface
{
    /**
     * Извлекает данные из указанного в первом параметре архива по
     * указанному во втором параметре пути.
     *
     * @param \marvin255\fias\service\filesystem\FileInterface      $source
     * @param \marvin255\fias\service\filesystem\DirectoryInterface $destination
     *
     * @return void
     *
     * @throws \RuntimeException
     */
    public function unpack(FileInterface $source, DirectoryInterface $destination);
}
