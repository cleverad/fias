<?php

declare(strict_types=1);

namespace marvin255\fias\service\fias;

use SoapClient;

/**
 * Объект, который получает ссылку на файл с архивом ФИАС
 * от soap сервиса информирования ФИАС.
 */
class Informer implements InformerInterface
{
    /**
     * @const
     */
    const DEFAULT_FIAS_WSDL = 'http://fias.nalog.ru/WebServices/Public/DownloadService.asmx?WSDL';
    /**
     * @var SoapClient
     */
    protected $soapClient;

    /**
     * Задает SoapClient, если объект не задан, то создает самостоятельно.
     *
     * @param SoapClient $soapClient
     */
    public function __construct(SoapClient $soapClient = null)
    {
        if ($soapClient === null) {
            $soapClient = new SoapClient(self::DEFAULT_FIAS_WSDL, [
                'exceptions' => true,
            ]);
        }

        $this->soapClient = $soapClient;
    }

    /**
     * @inheritdoc
     */
    public function getCompleteInfo(): InformerResultInterface
    {
        $response = $this->soapClient->GetLastDownloadFileInfo();

        $res = new InformerResult;
        $res->setVersion((int) $response->GetLastDownloadFileInfoResult->VersionId);
        $res->setUrl($response->GetLastDownloadFileInfoResult->FiasCompleteXmlUrl);

        return $res;
    }

    /**
     * @inheritdoc
     */
    public function getDeltaInfo(int $version): InformerResultInterface
    {
        $response = $this->soapClient->GetAllDownloadFileInfo();
        $versions = $this->sortResponseByVersion($response->GetAllDownloadFileInfoResult->DownloadFileInfo);

        $res = new InformerResult;
        foreach ($versions as $serviceVersion) {
            if ((int) $serviceVersion['VersionId'] <= $version) {
                continue;
            }
            $res->setVersion((int) $serviceVersion['VersionId']);
            $res->setUrl($serviceVersion['FiasDeltaXmlUrl']);
            break;
        }

        return $res;
    }

    /**
     * Сортирует ответ по номерам версии по возрастанию.
     *
     * @param array $response
     *
     * @return array
     */
    protected function sortResponseByVersion(array $response): array
    {
        $versions = [];
        $versionsSort = [];
        foreach ($response as $key => $version) {
            $versions[$key] = (array) $version;
            $versionsSort[$key] = (int) $version->VersionId;
        }
        array_multisort($versionsSort, SORT_ASC, $versions);

        return $versions;
    }
}
