<?php

declare(strict_types=1);

namespace marvin255\fias\task;

use marvin255\fias\service\fias\InformerResultInterface;
use marvin255\fias\state\StateInterface;
use InvalidArgumentException;
use Exception;

/**
 * Задача для загрузки архива с изменениями ФИАС относительно указанной версии.
 */
class DownloadDelta extends DownloadFull
{
    /**
     * Получает ссылку на файл с изменениями в базе относительно версии из сервиса ФИАС.
     *
     * @throws Exception
     */
    protected function fetchInfoFromService(StateInterface $state): InformerResultInterface
    {
        $informerResult = $state->getParameter('informerResult');
        if (!($informerResult instanceof InformerResultInterface)) {
            throw new InvalidArgumentException(
                'There is no informerResult parameter in state object'
            );
        }

        $currentVersion = (int) $informerResult->getVersion();

        $this->info("Fetching delta archive url current version {$currentVersion} from fias information service");

        return $this->informer->getDeltaInfo($currentVersion);
    }
}
