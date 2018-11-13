<?php

declare(strict_types=1);

namespace marvin255\fias\tests\mapper\fias;

use marvin255\fias\mapper\MapperInterface;
use marvin255\fias\mapper\fias\RoomTypes;

/**
 * Тест маппера RoomTypes.
 */
class RoomTypesTest extends MapperCase
{
    /**
     * Возвращает данные для проверки извлечения из xml.
     */
    protected function getXmlTestData(): array
    {
        $data = [
            'RMTYPEID' => $this->faker()->uuid,
            'NAME' => $this->faker()->word,
            'SHORTNAME' => $this->faker()->word,
        ];

        $xml = '<RoomType';
        $xml .= " RMTYPEID=\"{$data['RMTYPEID']}\"";
        $xml .= " NAME=\"{$data['NAME']}\"";
        $xml .= " SHORTNAME=\"{$data['SHORTNAME']}\"";
        $xml .= ' NEVER_GET_ME="NEVER_GET_ME"';
        $xml .= ' />';

        return [$data, $xml];
    }

    /**
     * Возвращает объект маппера.
     */
    protected function getMapper(): MapperInterface
    {
        return new RoomTypes;
    }
}
