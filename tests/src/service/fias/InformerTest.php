<?php

declare(strict_types=1);

namespace marvin255\fias\tests\service\fias;

use marvin255\fias\tests\BaseTestCase;
use marvin255\fias\service\fias\Informer;
use stdClass;
use SoapClient;

/**
 * Тест для объекта, который получает ссылку на файл с архивом ФИАС
 * от soap сервиса информирования ФИАС.
 */
class InformerTest extends BaseTestCase
{
    /**
     * Проверяет, что информер возвращает ссылку на полный файл ФИАС.
     */
    public function testGetCompleteInfo()
    {
        $soapResponse = new stdClass;
        $soapResponse->GetLastDownloadFileInfoResult = new stdClass;
        $soapResponse->GetLastDownloadFileInfoResult->FiasCompleteXmlUrl = $this->faker()->unique()->url;
        $soapResponse->GetLastDownloadFileInfoResult->VersionId = $this->faker()->unique()->randomNumber;

        $soapClient = $this->getMockBuilder(SoapClient::class)
            ->setMethods(['GetLastDownloadFileInfo'])
            ->disableOriginalConstructor()
            ->getMock();
        $soapClient->method('GetLastDownloadFileInfo')->will($this->returnValue($soapResponse));

        $service = new Informer($soapClient);
        $result = $service->getCompleteInfo();

        $this->assertSame(
            $soapResponse->GetLastDownloadFileInfoResult->FiasCompleteXmlUrl,
            $result->getUrl()
        );
        $this->assertSame(
            $soapResponse->GetLastDownloadFileInfoResult->VersionId,
            $result->getVersion()
        );
    }

    /**
     * Проверяет, что информер возвращает ссылку на дельту для указанной версии.
     */
    public function testGetDeltaInfo()
    {
        $soapResponse = new stdClass;
        $soapResponse->GetAllDownloadFileInfoResult = new stdClass;
        $soapResponse->GetAllDownloadFileInfoResult->DownloadFileInfo = [];

        $totalDeltas = 10;
        $currentDelta = $this->faker()->unique()->numberBetween(1, $totalDeltas - 1);
        $nextDelta = $currentDelta + 1;
        $nextUrl = null;
        for ($i = 1; $i <= $totalDeltas; ++$i) {
            $delta = new stdClass;
            $delta->VersionId = $i;
            $delta->FiasDeltaXmlUrl = $this->faker()->unique()->url;
            $soapResponse->GetAllDownloadFileInfoResult->DownloadFileInfo[] = $delta;
            if ($i === $nextDelta) {
                $nextUrl = $delta->FiasDeltaXmlUrl;
            }
        }
        shuffle($soapResponse->GetAllDownloadFileInfoResult->DownloadFileInfo);

        $soapClient = $this->getMockBuilder(SoapClient::class)
            ->setMethods(['GetAllDownloadFileInfo'])
            ->disableOriginalConstructor()
            ->getMock();
        $soapClient->method('GetAllDownloadFileInfo')->will($this->returnValue($soapResponse));

        $service = new Informer($soapClient);
        $result = $service->getDeltaInfo($currentDelta);

        $this->assertSame($nextUrl, $result->getUrl());
        $this->assertSame($nextDelta, $result->getVersion());
    }
}
