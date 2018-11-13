<?php

declare(strict_types=1);

namespace marvin255\fias\tests\mapper\fias;

use marvin255\fias\mapper\MapperInterface;
use marvin255\fias\mapper\fias\ActualStatuses;

/**
 * Тест маппера ActualStatuses.
 */
class ActualStatusesTest extends MapperCase
{
    /**
     * Возвращает данные для проверки извлечения из xml.
     */
    protected function getXmlTestData(): array
    {
        $data = [
            'ACTSTATID' => $this->faker()->uuid,
            'NAME' => $this->faker()->word,
        ];

        $xml = '<ActualStatus';
        $xml .= " ACTSTATID=\"{$data['ACTSTATID']}\"";
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
        return new ActualStatuses;
    }
}
