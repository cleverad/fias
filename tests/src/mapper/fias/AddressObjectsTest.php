<?php

declare(strict_types=1);

namespace marvin255\fias\tests\mapper\fias;

use marvin255\fias\mapper\fias\AddressObjects;
use marvin255\fias\mapper\MapperInterface;
use DateTime;

/**
 * Тест маппера AddressObjects.
 */
class AddressObjectsTest extends MapperCase
{
    /**
     * Возвращает данные для проверки извлечения из xml.
     */
    protected function getTestData(): array
    {
        return [
            'AOID' => $this->faker()->uuid,
            'AOGUID' => $this->faker()->uuid,
            'PARENTGUID' => $this->faker()->uuid,
            'NEXTID' => $this->faker()->uuid,
            'FORMALNAME' => $this->faker()->word,
            'OFFNAME' => $this->faker()->word,
            'SHORTNAME' => $this->faker()->word,
            'AOLEVEL' => $this->faker()->randomDigitNotNull,
            'REGIONCODE' => $this->faker()->lexify('??'),
            'AREACODE' => $this->faker()->lexify('???'),
            'AUTOCODE' => $this->faker()->lexify('?'),
            'CITYCODE' => $this->faker()->lexify('???'),
            'CTARCODE' => $this->faker()->lexify('???'),
            'PLACECODE' => $this->faker()->lexify('????'),
            'PLANCODE' => $this->faker()->lexify('????'),
            'STREETCODE' => $this->faker()->lexify('????'),
            'EXTRCODE' => $this->faker()->lexify('????'),
            'SEXTCODE' => $this->faker()->lexify('???'),
            'PLAINCODE' => $this->faker()->text(15),
            'CURRSTATUS' => $this->faker()->randomDigitNotNull,
            'ACTSTATUS' => $this->faker()->randomDigitNotNull,
            'LIVESTATUS' => $this->faker()->randomDigitNotNull,
            'CENTSTATUS' => $this->faker()->randomDigitNotNull,
            'OPERSTATUS' => $this->faker()->randomDigitNotNull,
            'IFNSFL' => $this->faker()->lexify('????'),
            'IFNSUL' => $this->faker()->lexify('????'),
            'TERRIFNSFL' => $this->faker()->lexify('????'),
            'TERRIFNSUL' => $this->faker()->lexify('????'),
            'OKATO' => $this->faker()->text(11),
            'OKTMO' => $this->faker()->text(11),
            'POSTALCODE' => $this->faker()->text(6),
            'STARTDATE' => new DateTime($this->faker()->date),
            'ENDDATE' => new DateTime($this->faker()->date),
            'UPDATEDATE' => new DateTime($this->faker()->date),
            'DIVTYPE' => $this->faker()->randomDigitNotNull,
        ];
    }

    /**
     * Возвращает строку с xml на основании входного параметра.
     */
    protected function getTestXml(array $data): string
    {
        $xml = '<Object';
        $xml .= " AOID=\"{$data['AOID']}\"";
        $xml .= " AOGUID=\"{$data['AOGUID']}\"";
        $xml .= " PARENTGUID=\"{$data['PARENTGUID']}\"";
        $xml .= " NEXTID=\"{$data['NEXTID']}\"";
        $xml .= " FORMALNAME=\"{$data['FORMALNAME']}\"";
        $xml .= " OFFNAME=\"{$data['OFFNAME']}\"";
        $xml .= " SHORTNAME=\"{$data['SHORTNAME']}\"";
        $xml .= " AOLEVEL=\"{$data['AOLEVEL']}\"";
        $xml .= " REGIONCODE=\"{$data['REGIONCODE']}\"";
        $xml .= " AREACODE=\"{$data['AREACODE']}\"";
        $xml .= " AUTOCODE=\"{$data['AUTOCODE']}\"";
        $xml .= " CITYCODE=\"{$data['CITYCODE']}\"";
        $xml .= " CTARCODE=\"{$data['CTARCODE']}\"";
        $xml .= " PLACECODE=\"{$data['PLACECODE']}\"";
        $xml .= " PLANCODE=\"{$data['PLANCODE']}\"";
        $xml .= " STREETCODE=\"{$data['STREETCODE']}\"";
        $xml .= " EXTRCODE=\"{$data['EXTRCODE']}\"";
        $xml .= " SEXTCODE=\"{$data['SEXTCODE']}\"";
        $xml .= " PLAINCODE=\"{$data['PLAINCODE']}\"";
        $xml .= " CURRSTATUS=\"{$data['CURRSTATUS']}\"";
        $xml .= " ACTSTATUS=\"{$data['ACTSTATUS']}\"";
        $xml .= " LIVESTATUS=\"{$data['LIVESTATUS']}\"";
        $xml .= " CENTSTATUS=\"{$data['CENTSTATUS']}\"";
        $xml .= " OPERSTATUS=\"{$data['OPERSTATUS']}\"";
        $xml .= " IFNSFL=\"{$data['IFNSFL']}\"";
        $xml .= " IFNSUL=\"{$data['IFNSUL']}\"";
        $xml .= " TERRIFNSFL=\"{$data['TERRIFNSFL']}\"";
        $xml .= " TERRIFNSUL=\"{$data['TERRIFNSUL']}\"";
        $xml .= " OKATO=\"{$data['OKATO']}\"";
        $xml .= " OKTMO=\"{$data['OKTMO']}\"";
        $xml .= " POSTALCODE=\"{$data['POSTALCODE']}\"";
        $xml .= ' STARTDATE="' . $data['STARTDATE']->format('d.m.Y') . '"';
        $xml .= ' ENDDATE="' . $data['ENDDATE']->format('d.m.Y') . '"';
        $xml .= ' UPDATEDATE="' . $data['UPDATEDATE']->format('d.m.Y') . '"';
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
        return new AddressObjects;
    }
}
