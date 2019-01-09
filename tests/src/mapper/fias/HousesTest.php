<?php

declare(strict_types=1);

namespace marvin255\fias\tests\mapper\fias;

use marvin255\fias\mapper\fias\Houses;
use marvin255\fias\mapper\MapperInterface;
use DateTime;

/**
 * Тест маппера Houses.
 */
class HousesTest extends MapperCase
{
    /**
     * Возвращает данные для проверки извлечения из xml.
     */
    protected function getTestData(): array
    {
        return [
            'HOUSEID' => $this->faker()->uuid,
            'HOUSEGUID' => $this->faker()->uuid,
            'AOGUID' => $this->faker()->uuid,
            'HOUSENUM' => $this->faker()->text(20),
            'STRSTATUS' => $this->faker()->randomDigitNotNull,
            'ESTSTATUS' => $this->faker()->randomDigitNotNull,
            'STATSTATUS' => $this->faker()->randomDigitNotNull,
            'STRSTATUS' => $this->faker()->randomDigitNotNull,
            'ESTSTATUS' => $this->faker()->randomDigitNotNull,
            'STATSTATUS' => $this->faker()->randomDigitNotNull,
            'IFNSFL' => $this->faker()->lexify('????'),
            'IFNSUL' => $this->faker()->lexify('????'),
            'OKATO' => $this->faker()->text(11),
            'OKTMO' => $this->faker()->text(11),
            'POSTALCODE' => $this->faker()->text(6),
            'STARTDATE' => new DateTime($this->faker()->date),
            'ENDDATE' => new DateTime($this->faker()->date),
            'UPDATEDATE' => new DateTime($this->faker()->date),
            'COUNTER' => $this->faker()->randomDigitNotNull,
            'DIVTYPE' => $this->faker()->randomDigitNotNull,
        ];
    }

    /**
     * Возвращает строку с xml на основании входного параметра.
     */
    protected function getTestXml(array $data): string
    {
        $xml = '<House';
        $xml .= " HOUSEID=\"{$data['HOUSEID']}\"";
        $xml .= " HOUSEGUID=\"{$data['HOUSEGUID']}\"";
        $xml .= " AOGUID=\"{$data['AOGUID']}\"";
        $xml .= " HOUSENUM=\"{$data['HOUSENUM']}\"";
        $xml .= " STRSTATUS=\"{$data['STRSTATUS']}\"";
        $xml .= " ESTSTATUS=\"{$data['ESTSTATUS']}\"";
        $xml .= " STATSTATUS=\"{$data['STATSTATUS']}\"";
        $xml .= " IFNSFL=\"{$data['IFNSFL']}\"";
        $xml .= " IFNSUL=\"{$data['IFNSUL']}\"";
        $xml .= " OKATO=\"{$data['OKATO']}\"";
        $xml .= " OKTMO=\"{$data['OKTMO']}\"";
        $xml .= " POSTALCODE=\"{$data['POSTALCODE']}\"";
        $xml .= ' STARTDATE="' . $data['STARTDATE']->format('d.m.Y') . '"';
        $xml .= ' ENDDATE="' . $data['ENDDATE']->format('d.m.Y') . '"';
        $xml .= ' UPDATEDATE="' . $data['UPDATEDATE']->format('d.m.Y') . '"';
        $xml .= " COUNTER=\"{$data['COUNTER']}\"";
        $xml .= " DIVTYPE=\"{$data['DIVTYPE']}\"";
        $xml .= ' NEVER_GET_ME="NEVER_GET_ME"';
        $xml .= ' />';

        return $xml;
    }

    /**
     * Возвращает объект маппера.
     */
    protected function getMapper(): MapperInterface
    {
        return new Houses;
    }
}
