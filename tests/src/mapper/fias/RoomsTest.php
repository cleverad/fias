<?php

declare(strict_types=1);

namespace marvin255\fias\tests\mapper\fias;

use marvin255\fias\mapper\fias\Rooms;
use marvin255\fias\mapper\MapperInterface;
use DateTime;

/**
 * Тест маппера Rooms.
 */
class RoomsTest extends MapperCase
{
    /**
     * Возвращает данные для проверки извлечения из xml.
     */
    protected function getTestData(): array
    {
        return [
            'ROOMID' => $this->faker()->uuid,
            'ROOMGUID' => $this->faker()->uuid,
            'HOUSEGUID' => $this->faker()->uuid,
            'REGIONCODE' => $this->faker()->lexify('??'),
            'FLATNUMBER' => $this->faker()->text(50),
            'FLATTYPE' => $this->faker()->randomDigitNotNull,
            'POSTALCODE' => $this->faker()->text(6),
            'STARTDATE' => new DateTime($this->faker()->date),
            'ENDDATE' => new DateTime($this->faker()->date),
            'UPDATEDATE' => new DateTime($this->faker()->date),
            'OPERSTATUS' => $this->faker()->word,
            'LIVESTATUS' => $this->faker()->word,
            'NORMDOC' => $this->faker()->uuid,
        ];
    }

    /**
     * Возвращает строку с xml на основании входного параметра.
     */
    protected function getTestXml(array $data): string
    {
        $xml = '<Room';
        $xml .= " ROOMID=\"{$data['ROOMID']}\"";
        $xml .= " ROOMGUID=\"{$data['ROOMGUID']}\"";
        $xml .= " HOUSEGUID=\"{$data['HOUSEGUID']}\"";
        $xml .= " REGIONCODE=\"{$data['REGIONCODE']}\"";
        $xml .= " FLATNUMBER=\"{$data['FLATNUMBER']}\"";
        $xml .= " FLATTYPE=\"{$data['FLATTYPE']}\"";
        $xml .= " POSTALCODE=\"{$data['POSTALCODE']}\"";
        $xml .= ' STARTDATE="' . $data['STARTDATE']->format('d.m.Y') . '"';
        $xml .= ' ENDDATE="' . $data['ENDDATE']->format('d.m.Y') . '"';
        $xml .= ' UPDATEDATE="' . $data['UPDATEDATE']->format('d.m.Y') . '"';
        $xml .= " OPERSTATUS=\"{$data['OPERSTATUS']}\"";
        $xml .= " LIVESTATUS=\"{$data['LIVESTATUS']}\"";
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
        return new Rooms;
    }
}
