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
            'HOUSENUM' => $this->faker()->word,
            'STRSTATUS' => $this->faker()->randomDigitNotNull,
            'ESTSTATUS' => $this->faker()->randomDigitNotNull,
            'STATSTATUS' => $this->faker()->randomDigitNotNull,
            'HOUSENUM' => $this->faker()->word,
            'STRSTATUS' => $this->faker()->randomDigitNotNull,
            'ESTSTATUS' => $this->faker()->randomDigitNotNull,
            'STATSTATUS' => $this->faker()->randomDigitNotNull,
            'IFNSFL' => $this->faker()->word,
            'IFNSUL' => $this->faker()->word,
            'OKATO' => $this->faker()->randomDigitNotNull,
            'OKTMO' => $this->faker()->randomDigitNotNull,
            'POSTALCODE' => $this->faker()->word,
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
