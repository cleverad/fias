<?php

declare(strict_types=1);

namespace marvin255\fias\tests\mapper\fias;

use marvin255\fias\mapper\MapperInterface;
use marvin255\fias\mapper\fias\CurrentStatus;

/**
 * Тест маппера CurrentStatus.
 */
class CurrentStatusTest extends MapperCase
{
    /**
     * Возвращает данные для проверки извлечения из xml.
     */
    protected function getXmlTestData(): array
    {
        $data = [
            'CURENTSTID' => $this->faker()->uuid,
            'NAME' => $this->faker()->word,
        ];

        $xml = '<CurrentStatus';
        $xml .= " CURENTSTID=\"{$data['CURENTSTID']}\"";
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
        return new CurrentStatus;
    }
}
