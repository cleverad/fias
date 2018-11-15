<?php

declare(strict_types=1);

namespace marvin255\fias\tests\mapper\fias;

use marvin255\fias\mapper\fias\Steads;
use marvin255\fias\mapper\MapperInterface;
use DateTime;

/**
 * Тест маппера Steads.
 */
class SteadsTest extends MapperCase
{
    /**
     * Возвращает данные для проверки извлечения из xml.
     */
    protected function getTestData(): array
    {
        return [
            'STEADGUID' => $this->faker()->uuid,
            'NUMBER' => $this->faker()->word,
            'REGIONCODE' => $this->faker()->word,
            'POSTALCODE' => $this->faker()->word,
            'IFNSFL' => $this->faker()->word,
            'IFNSUL' => $this->faker()->word,
            'OKATO' => $this->faker()->word,
            'OKTMO' => $this->faker()->word,
            'PARENTGUID' => $this->faker()->uuid,
            'STEADID' => $this->faker()->uuid,
            'OPERSTATUS' => $this->faker()->word,
            'STARTDATE' => new DateTime($this->faker()->date),
            'ENDDATE' => new DateTime($this->faker()->date),
            'UPDATEDATE' => new DateTime($this->faker()->date),
            'LIVESTATUS' => $this->faker()->word,
            'DIVTYPE' => $this->faker()->word,
            'NORMDOC' => $this->faker()->uuid,
        ];
    }

    /**
     * Возвращает строку с xml на основании входного параметра.
     */
    protected function getTestXml(array $data): string
    {
        $xml = '<Stead';
        $xml .= " STEADGUID=\"{$data['STEADGUID']}\"";
        $xml .= " NUMBER=\"{$data['NUMBER']}\"";
        $xml .= " REGIONCODE=\"{$data['REGIONCODE']}\"";
        $xml .= " POSTALCODE=\"{$data['POSTALCODE']}\"";
        $xml .= " IFNSFL=\"{$data['IFNSFL']}\"";
        $xml .= " IFNSUL=\"{$data['IFNSUL']}\"";
        $xml .= " OKATO=\"{$data['OKATO']}\"";
        $xml .= " OKTMO=\"{$data['OKTMO']}\"";
        $xml .= " PARENTGUID=\"{$data['PARENTGUID']}\"";
        $xml .= " STEADID=\"{$data['STEADID']}\"";
        $xml .= " OPERSTATUS=\"{$data['OPERSTATUS']}\"";
        $xml .= ' STARTDATE="' . $data['STARTDATE']->format('d.m.Y') . '"';
        $xml .= ' ENDDATE="' . $data['ENDDATE']->format('d.m.Y') . '"';
        $xml .= ' UPDATEDATE="' . $data['UPDATEDATE']->format('d.m.Y') . '"';
        $xml .= " LIVESTATUS=\"{$data['LIVESTATUS']}\"";
        $xml .= " DIVTYPE=\"{$data['DIVTYPE']}\"";
        $xml .= " NORMDOC=\"{$data['NORMDOC']}\"";
        $xml .= ' NEVER_GET_ME="NEVER_GET_ME"';
        $xml .= ' />';

        return $xml;
    }

    /**
     * Возвращает объект маппера.
     */
    protected function getMapper(): MapperInterface
    {
        return new Steads;
    }
}
