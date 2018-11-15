<?php

declare(strict_types=1);

namespace marvin255\fias\tests\mapper\fias;

use marvin255\fias\mapper\MapperInterface;
use marvin255\fias\mapper\fias\StructureStatuses;

/**
 * Тест маппера StructureStatuses.
 */
class StructureStatusesTest extends MapperCase
{
    /**
     * Возвращает данные для проверки извлечения из xml.
     */
    protected function getTestData(): array
    {
        return [
            'STRSTATID' => $this->faker()->uuid,
            'NAME' => $this->faker()->word,
            'SHORTNAME' => $this->faker()->word,
        ];
    }

    /**
     * Возвращает строку с xml на основании входного параметра.
     */
    protected function getTestXml(array $data): string
    {
        $xml = '<StructureStatus';
        $xml .= " STRSTATID=\"{$data['STRSTATID']}\"";
        $xml .= " NAME=\"{$data['NAME']}\"";
        $xml .= " SHORTNAME=\"{$data['SHORTNAME']}\"";
        $xml .= ' NEVER_GET_ME="NEVER_GET_ME"';
        $xml .= ' />';

        return $xml;
    }

    /**
     * Возвращает объект маппера.
     */
    protected function getMapper(): MapperInterface
    {
        return new StructureStatuses;
    }
}
