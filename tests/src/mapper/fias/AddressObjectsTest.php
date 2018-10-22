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
    protected function getXmlTestData(): array
    {
        $data = [
            'AOID' => $this->faker()->unique()->uuid,
            'AOGUID' => $this->faker()->unique()->uuid,
            'PARENTGUID' => $this->faker()->unique()->uuid,
            'NEXTID' => $this->faker()->unique()->uuid,
            'FORMALNAME' => $this->faker()->unique()->word,
            'OFFNAME' => $this->faker()->unique()->word,
            'SHORTNAME' => $this->faker()->unique()->word,
            'AOLEVEL' => $this->faker()->unique()->randomDigit,
            'REGIONCODE' => $this->faker()->unique()->word,
            'AREACODE' => $this->faker()->unique()->word,
            'AUTOCODE' => $this->faker()->unique()->word,
            'CITYCODE' => $this->faker()->unique()->word,
            'CTARCODE' => $this->faker()->unique()->word,
            'PLACECODE' => $this->faker()->unique()->word,
            'PLANCODE' => $this->faker()->unique()->word,
            'STREETCODE' => $this->faker()->unique()->word,
            'EXTRCODE' => $this->faker()->unique()->word,
            'SEXTCODE' => $this->faker()->unique()->word,
            'PLAINCODE' => $this->faker()->unique()->word,
            'CURRSTATUS' => $this->faker()->unique()->randomDigit,
            'ACTSTATUS' => $this->faker()->unique()->randomDigit,
            'LIVESTATUS' => $this->faker()->unique()->randomDigit,
            'CENTSTATUS' => $this->faker()->unique()->randomDigit,
            'OPERSTATUS' => $this->faker()->unique()->randomDigit,
            'IFNSFL' => $this->faker()->unique()->word,
            'IFNSUL' => $this->faker()->unique()->word,
            'TERRIFNSFL' => $this->faker()->unique()->word,
            'TERRIFNSUL' => $this->faker()->unique()->word,
            'OKATO' => $this->faker()->unique()->word,
            'OKTMO' => $this->faker()->unique()->word,
            'POSTALCODE' => $this->faker()->unique()->word,
            'STARTDATE' => new DateTime($this->faker()->unique()->date),
            'ENDDATE' => new DateTime($this->faker()->unique()->date),
            'UPDATEDATE' => new DateTime($this->faker()->unique()->date),
            'DIVTYPE' => $this->faker()->unique()->randomDigit,
        ];

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

        return [$data, $xml];
    }

    /**
     * Возвращает объект маппера.
     */
    protected function getMapper(): MapperInterface
    {
        return new AddressObjects;
    }
}
