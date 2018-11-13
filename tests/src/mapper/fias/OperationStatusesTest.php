<?php

declare(strict_types=1);

namespace marvin255\fias\tests\mapper\fias;

use marvin255\fias\mapper\MapperInterface;
use marvin255\fias\mapper\fias\OperationStatuses;

/**
 * Тест маппера OperationStatuses.
 */
class OperationStatusesTest extends MapperCase
{
    /**
     * Возвращает данные для проверки извлечения из xml.
     */
    protected function getXmlTestData(): array
    {
        $data = [
            'OPERSTATID' => $this->faker()->uuid,
            'NAME' => $this->faker()->word,
        ];

        $xml = '<OperationStatus';
        $xml .= " OPERSTATID=\"{$data['OPERSTATID']}\"";
        $xml .= " NAME=\"{$data['NAME']}\"";
        $xml .= ' NEVER_GET_ME="NEVER_GET_ME"';
        $xml .= ' />';

        return [$data, $xml];
    }

    /**
     * Возвращает объект маппера.
     */
    protected function getMapper(): MapperInterface
    {
        return new OperationStatuses;
    }
}
